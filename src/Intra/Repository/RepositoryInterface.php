<?php

namespace Intra\Repository;

interface RepositoryInterface {

    public function all($columns = ['*'], $order = 'date', $orderType = 'desc');

    public function paginate($take = 10, $skip = 0, $columns = ['*'], $order = 'date', $orderType = 'desc');

    public function create(array $data);

    public function update(array $data, $id);

    public function delete($id);

    public function find($id, $columns = ['*']);

    public function count();

}