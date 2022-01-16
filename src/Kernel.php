<?php

declare(strict_types = 1);

namespace Funyx\CommissionFeeCalculation;

use Funyx\CommissionFeeCalculation\Accounting\DataSet;
use Funyx\CommissionFeeCalculation\Accounting\Operation;
use SplFileObject;
use Symfony\Component\String\Exception\RuntimeException;

class Kernel
{

	private DataSet $data_set;
	private string $list_type = DataSet::class;

	public function loadFile( string $sys_path = '', string $type = 'CSV', string $list_type = DataSet::class ): void
	{
		$this->list_type = $list_type;
		$file = new SplFileObject($sys_path, 'r');
		switch ($file->getExtension()) {
			case 'csv':
			{
				$data_set = [];
				$file->setFlags(SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);
				while ( !$file->eof()) {
					$data_set[] = $file->fgetcsv();
				}
				$this->data_set = new $list_type($data_set);
				break;
			}
			// TODO other file types
			default :
				throw new RuntimeException(sprintf('Unsupported file type %s', $type));
		}
	}

	public function run( Operation $operation, bool $stdout = true ): ?\Generator
	{
		$operation->setData($this->data_set);
		$operation->setListType($this->list_type);
		if( $stdout ){
			foreach ($operation->run() as $output) {
				print $output.PHP_EOL;
			}
		}else{
			return $operation->run();
		}
	}
}
