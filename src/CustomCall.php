<?php
namespace Scheduler;
//自定义调用
Class CustomCall{
	protected $callback;

    CONST RESULT_KEY = '@result';

	public function __construct(callback $callback){
		$this->callback = $callback;
	}

	public function __invoke(Task $task, Scheduler $scheduler) {
        $callback = $this->callback;
        return $callback($task, $scheduler);
    }

    public static function returnReust($data){
    	return [self::RESULT_KEY, $data];
    }
}