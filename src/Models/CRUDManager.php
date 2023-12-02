<?php

declare(strict_types=1);

namespace Portfolio\Ntimbablog\Models;

use Portfolio\Ntimbablog\Helpers\StringUtil;
use Portfolio\Ntimbablog\Lib\Database;

abstract class CRUDManager {

    protected Database $db;
    protected StringUtil $stringUtil;

    public function __construct(Database $db, StringUtil $stringUtil)
    {
        $this->db = $db;
        $this->stringUtil = $stringUtil;
    }

    public abstract function read(int $id): Object|bool;

    public abstract function create(Object $data): ?bool;

    public abstract function update(Object $data): ?bool;

    public abstract function delete(int $id): ?bool;

}


