<?php
/**
 * 用户业务类
 */
class core_service_user extends core_service {
    protected $id;
    protected $marter_table = 'core_model_user';

    /**
     * 实例化对象
     * @param int $id
     */
    public function __construct($id = 0) {
        parent::__construct();
        $this->id = $id;
    }

    /**
     * 注册
     */
    function register(){
        //
    }
}
