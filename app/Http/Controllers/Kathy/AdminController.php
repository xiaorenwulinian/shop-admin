<?php

namespace App\Http\Controllers\Kathy;

use App\Model\Kathy\Admin;
use App\Model\Kathy\MyAbility;
use App\Model\Kathy\MyAssignedRole;
use App\Model\Kathy\MyPermission;
use App\Model\Kathy\MyRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Silber\Bouncer\Bouncer;

class AdminController extends Controller
{

    public function __construct(Request $request, Admin $model)
    {
        parent::__construct($request, $model);
    }

    /**
     * 列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page = $this->request->get('page', 1);
        $per_page = $this->request->get('per_page', 20);
        $query = Admin::query();
        $total = $query->count('id');
        $list = $query->with('role:roles.id,title')->forPage($page, $per_page)->orderByDesc('id')->select('id', 'username', 'phone', 'status', 'avatar')->get();

        return res_success(['total' => $total, 'list' => $list]);
    }

    /**
     * 新增
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validation([
            'phone' => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);
        $param = $this->getPostData();
        $role_ids = $this->request->get('role_ids');
        $param['password'] = $param['password'] ? password_hash($param['password'], PASSWORD_BCRYPT) : '';
        $is_exist = Admin::query()->where('phone', $param['phone'])->count();
        abort_if($is_exist, 400, '该手机号已存在');
        DB::beginTransaction();
        try {
            $result = Admin::query()->create($param);
            $admin = Admin::query()->find($result['id']);
            if (!empty($role_ids)) {
                $role_ids = explode(',', $role_ids);
                //重新角色绑定角色
                $role_arr = MyRole::query()->whereIn('id', $role_ids)->get(['name'])->toArray();
                foreach ($role_arr as $item) {
                    $admin->assign($item['name']);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            abort(400, $e->getMessage());
        }

        return res_success([], '新增成功');
    }

    /**
     * 更新
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->validation([
            'username' => 'required',
        ]);
        $param = $this->getPostData();
        $new_password = $this->request->get('new_password');
        $role_ids = $this->request->get('role_ids');
        if (!empty($new_password)) {
            $this->validation([
                'new_password' => 'required|between:8,20',
                'password' => 'required',
            ]);
            $password = Admin::query()->where('id', $id)->value('password');
            !password_verify($param['password'], $password) && abort(400, '原密码错误');
            $param['password'] = password_hash($new_password, PASSWORD_BCRYPT);
        }

        DB::beginTransaction();
        try{
            //消除用户的角色
            $before_ids = MyAssignedRole::query()->where('entity_id', $id)->where('entity_type', 'App\Model\Kathy\Admin')->get(['role_id'])->pluck('role_id');
            $admin = Admin::query()->find($id);
            if (!empty($before_ids)) {
                $before_arr = MyRole::query()->whereIn('id', $before_ids)->get(['name'])->toArray();
                foreach ($before_arr as $item) {
                    $admin->retract($item['name']);
                }
            }
            if (!empty($role_ids)) {
                $role_ids = explode(',', $role_ids);
                //重新角色绑定角色
                $role_arr = MyRole::query()->whereIn('id', $role_ids)->get(['name'])->toArray();
                foreach ($role_arr as $item) {
                    $admin->assign($item['name']);
                }
            }
            Admin::query()->where('id', $id)->update($param);
            DB::commit();
            return res_success([], '更新成功');
        }catch (\Exception $e) {
            DB::rollBack();
            abort(400, $e->getMessage());
        }
    }

    /**
     * 详情
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Admin = Admin::query()->where('id', $id)->with(['group:id,name', 'role:id,title'])->firstOrFail();

        return res_success($Admin);
    }

    /**
     * 删除
     *
     * @param $id
     * @return false|\Illuminate\Http\Response|string
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $ret = Admin::query()->where('id', $id)->delete();
            abort_if(!$ret, 400, '删除失败');
            MyAssignedRole::query()->where('entity_id', $id)->where('entity_type', 'App\Model\Kathy\Admin')->delete();
            DB::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            abort(400, $e->getMessage());
        }

        return res_success([], '删除成功');
    }

    /**
     * 功能权限管理
     * @return \Illuminate\Http\JsonResponse
     */
    public function permission()
    {
        $total = MyAbility::query()->where('parent_id', 0)->count();
        $list = MyAbility::query()->where('parent_id', 0)->with(['child'])->get(['id', 'name', 'desc', 'parent_id']);

        return res_success(['total' => $total, 'list' => $list]);
    }

    /**
     * 新增权限/功能未测试
     * @return \Illuminate\Http\JsonResponse
     */
    public function storePermission()
    {
        $param = $this->getReqParams(['name', 'desc', 'parent_id']);
        $this->validation([
            'name' => 'required | unique:abilities',
        ]);
        if (is_numeric($this->request->post('name'))) {
            abort(400, '不能全为数字');
        }
        $result = MyAbility::query()->create($param);
        abort_if(!$result, 400, '新增权限失败');

        return res_success(['id' => $result->id], '新增权限成功');
    }

    /**
     * 修改权限
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePermission($id)
    {
        $param = $this->getReqParams(['name', 'desc', 'parent_id']);
        $this->validation([
            'name' => [
                'required',
                Rule::unique('abilities')->ignore($id),
            ],
        ]);
        $ability = MyAbility::query()->where('id', $id)->firstOrFail();
        $ability->name = $param['name'];
        $ability->desc = $param['desc'];
        $ability->parent_id = $param['parent_id'];
        $ability->save();

        return res_success(['id' => $id], '更新成功');
    }

    /**
     * 删除权限
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deletePermission($id)
    {
        $result = MyAbility::query()->where('id', $id)->delete();
        abort_if(!$result, 400, '删除失败');

        return res_success(['id' => $id], '删除成功');
    }


    /**
     * 角色管理
     * @return \Illuminate\Http\JsonResponse
     */
    public function role()
    {
        $name = $this->request->get('name');
        $query = MyRole::query();
        $name != '' && $query->where('name', 'like', "%{$name}%");
        $total = $query->count();
        $list = MyRole::query()->with('ability:abilities.id,name,desc')->select('id', 'name', 'title', 'menu')->get();
        $list->each(function ($item) {
            $item['menu'] = empty($item['menu']) ? [] : json_decode($item['menu'], true);
        });
        return res_success(['total' => $total, 'list' => $list]);
    }

    /**
     * 新增角色
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeRole()
    {
        $param = $this->getReqParams(['name', 'title', 'ability_ids', 'menu']);
        $this->validation([
            'name' => 'required|unique:roles',
            'title' => 'required',
        ]);
        $bouncer = Bouncer::create();
        //创建角色
        $role = $bouncer->role()->create([
            'name' => $param['name'],
            'title' => $param['title'],
        ]);
        abort_if(!$role, 400, '创建失败');

        MyRole::query()->where('id', $role->id)->update(['menu' => $param['menu']]);
        //赋予角色一些权限
        if (!empty($param['ability_ids'])) {
            $param['ability_ids'] = explode(',', $param['ability_ids']);
            $ability_arr = MyAbility::query()->whereIn('id', $param['ability_ids'])->get(['name'])->toArray();
            foreach ($ability_arr as $item) {
                $bouncer->allow($role)->to($item['name']);
            }
        }

        return res_success(['id' => $role->id]);
    }

    /**
     * 更新角色以及绑定的权限
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRole($id)
    {
        $param = $this->getReqParams(['name', 'title', 'ability_ids', 'menu']);
        $this->validation([
            'name' => [
                'required',
                Rule::unique('roles')->ignore($id),
            ],
            'title' => 'required',
        ]);
        $check = MyRole::query()->where('id', $id)->count('id');
        abort_if(!$check, 400, '未找到该角色信息');

        DB::beginTransaction();
        try{
            $bouncer = Bouncer::create();
            //更新角色
            $role = $bouncer->role()->where('id', $id)->update([
                'name' => $param['name'],
                'title' => $param['title'],
                'menu' => $param['menu'],
            ]);
            abort_if(!$role, 400, '更新失败');

            //消除角色下的权限
            $before_ids = MyPermission::query()->where('entity_id', $id)->where('entity_type', 'roles')->get(['ability_id'])->pluck('ability_id');
            if (!empty($before_ids)) {
                $before_arr = MyAbility::query()->whereIn('id', $before_ids)->get(['name'])->toArray();
                foreach ($before_arr as $item) {
                    $bouncer->disallow($param['name'])->to($item['name']);
                }
            }

            if (!empty($param['ability_ids'])) {
                //重新角色绑定权限
                $param['ability_ids'] = explode(',', $param['ability_ids']);
                $ability_arr = MyAbility::query()->whereIn('id', $param['ability_ids'])->get(['name'])->toArray();
                foreach ($ability_arr as $item) {
                    $bouncer->allow($param['name'])->to($item['name']);
                }
            }
            DB::commit();
            return res_success(['id' => $id], '更新成功');
        }catch(\Exception $e) {
            DB::rollBack();
            return res_fail('更新失败');
        }
    }

    /**
     * 删除角色
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteRole($id)
    {
        if ($id == 1) {
            abort(400, '管理员角色不能删除');
        }
        MyRole::query()->where('id', $id)->delete();
        MyAssignedRole::query()->where('role_id', $id)->delete();
        MyPermission::query()->where('entity_id', $id)->where('entity_type', 'roles')->delete();

        return res_success(['id' => $id], '删除成功');
    }

    private function getPostData()
    {
        $data = $this->getReqParams([
            'avatar',
            'phone',
            'username',
            'password',
        ]);

        return $data;
    }
}
