<?php

namespace App\Http\Controllers\Kathy;

use App\Http\Controllers\Controller as CommonController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\MySqlBuilder;
use Illuminate\Http\Request;

class Controller extends CommonController
{
    /**
     * @var Model
     */
    protected $model;

    public function __construct(Request $request, $model)
    {
        parent::__construct($request);

        $this->model = $model;
    }

    /**
     * 列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = $this->model->newQuery();

        $param   = $this->request->all();
        $columns = MySqlBuilder::getColumnListing($this->model->getTable());

        // 搜索字段
        foreach ($param as $key => $item) {
            if (!in_array($key, $columns)) {
                unset($param[$key]);
            }
        }

        if ($param) {
            foreach ($param as $key => $value) {
                if ($value !== '') {
                    if (in_array($key, ['name', 'phone', 'title'])) {
                        $query->where($key, 'like', '%' . $value . '%');
                    } else {
                        $query->where($key, $value);
                    }
                }
            }
        }

        // 查询过滤
        $query = $this->filter($query);

        // 总数
        $total = $query->count('id');
        $list  = [];
        if ($total) {
            $page     = $this->request->get('page', 1);
            $per_page = $this->request->get('per_page', 20);

            $field = $this->request->get('_field', 'id');
            $order = $this->request->get('_order', 'desc');

            $list = $this->relation($query)->forPage($page, $per_page)->orderBy($field, $order)->get();
        }

        return res_success(['list' => $list, 'total' => $total]);
    }

    /**
     * 查询过滤
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filter($query)
    {
        // $query->where([])
        return $query;
    }

    /**
     * 关联查询
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function relation($query)
    {
        // $query->with([])
        return $query;
    }

    /**
     * 数据处理
     *
     * @param \Illuminate\Database\Eloquent\Collection $list
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function fieldBuild($list)
    {
        return $list;
    }

    /**
     * 创建
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        // 表单验证
        method_exists($this, 'formValidation') && $this->formValidation();
        $data = $this->request->all();
        $ret  = $this->model->fill($data)->save();

        abort_if(!$ret, 400, '创建失败');

        return res_success([], '创建成功');
    }

    /**
     * 详情
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->model->newQuery()->findOrFail($id);

        return res_success($data);
    }

    /**
     * 更新
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        // 表单验证
        method_exists($this, 'formValidation') && $this->formValidation();

        $data = $this->model->newQuery()->findOrFail($id);
        $data->update($this->request->all());

        return res_success([], '更新成功');
    }

    /**
     * 删除
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ret = $this->model->newQuery()->where('id', $id)->delete();
        abort_if(!$ret, 400, '删除失败');

        return res_success([], '删除成功');
    }

    /**
     * 禁用
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function forbid($id)
    {
        $data         = $this->model->newQuery()->findOrFail($id);
        $data->status = 0;
        $data->save();

        return res_success([], '禁用成功');
    }

    /**
     * 启用
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function resume($id)
    {
        $data         = $this->model->newQuery()->findOrFail($id);
        $data->status = 1;
        $data->save();

        return res_success([], '启用成功');
    }
}
