<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Model\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function lst()
    {
        $cur_page = request('page', 1);
        $page_size = request('page_size', 10);
        $page_size_select = page_size_select($page_size); //生成下拉选择页数框
        $list = DB::table('admin')->paginate($page_size);
        $list->appends(request()->input());
        $page = $list->render();

        return view('backend.admin.lst', compact('list', 'page_size_select', 'page', 'page_size', 'cur_page'));
    }

    public function add()
    {
        if ($this->request->method() == 'GET') {
            return view('backend.admin.add');
        } else {
            $this->validate($this->request, [
                'username' => 'required',
                'password' => 'required|between:6,8',
            ], [
                'username.required' => '用户名必填',
                'password.required' => '密码必填',
            ]);
            $data = request()->all();
            $data['password'] = Hash::make($data['password']);
            $res = Admin::query()->create($data);
            abort_if(!$res, 400, '新增失败');
            return redirect()->route('admin')->withErrors(['error' => '新增成功']);
        }
    }

    public function edit($id)
    {
        $data = Admin::query()->find($id)->toArray();
        if (request()->method() == 'GET') {
            return view('backend.admin.edit', compact('data'));
        }

        //编辑页提交数据
        $edit_data = request()->only(['username', 'password']);
        $this->validate($this->request, [
            'username' => 'required',
        ], [
            'username.required' => '用户名必填',
        ]);
        if ($edit_data['password']) {
            $edit_data['password'] = Hash::make($edit_data['password']);
        } else {
            unset($edit_data['password']);
        }
        $result = Admin::query()->where('id', $id)->update($edit_data);
        if (!$result) {
            return back()->withErrors(['error' => '编辑失败']);
        }

        return back()->withErrors(['error' => '编辑成功']);
    }

    public function delete($id)
    {
        $res = Admin::query()->where('id', $id)->delete();
        if (!$res) {
            return back()->withErrors(['error' => '删除失败']);
        } else {
            return back()->withErrors(['error' => '删除成功']);
        }
    }

    public function changeStatus()
    {
        $data = request()->all();

        $res = Admin::query()->where('id', $data['id'])->update(['is_use' => $data['is_use']]);
        if ($res) {
            return ['code'=>0, 'error'=>'success'];
        } else {
            return ['code'=>1, 'error'=>'error'];
        }
    }
}
