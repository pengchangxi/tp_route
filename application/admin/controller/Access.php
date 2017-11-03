<?php
namespace app\admin\controller;

use tree\Tree;
use think\Db;
use app\admin\model\Menu;

class Access extends Base{

    /**
     * 设置角色权限
     * @adminMenu(
     *     'name'   => '设置角色权限',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '设置角色权限',
     *     'param'  => ''
     * )
     */
    public function authorize(){
        if ($this->request->isPost()) {
            $roleId = $this->request->param("roleId", 0, 'intval');
            if (!$roleId) {
                $this->error("需要授权的角色不存在！");
            }
            if (is_array($this->request->param('menuId/a')) && count($this->request->param('menuId/a')) > 0) {

                Db::name("access")->where(["role_id" => $roleId, 'type' => 'admin_url'])->delete();
                foreach ($_POST['menuId'] as $menuId) {
                    $menu = Db::name("menu")->where(["id" => $menuId])->field("url")->find();
                    if ($menu) {
                        $name   = $menu['url'];
                        Db::name("access")->insert(["role_id" => $roleId, "rule_name" => $name, 'type' => 'admin_url']);
                    }
                }
                $this->success("授权成功！");
            } else {
                //当没有数据时，清除当前角色授权
                Db::name("access")->where(["role_id" => $roleId])->delete();
                $this->error("没有接收到数据，执行清除授权成功！");
            }
        }
        $AuthAccess     = Db::name("Access");
        $menuModel = new Menu();
        //角色ID
        $roleId = $this->request->param("id", 0, 'intval');
        if (empty($roleId)) {
            $this->error("参数错误！");
        }

        $tree       = new Tree();
        $tree->icon = ['│ ', '├─ ', '└─ '];
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';

        $result = $menuModel->menuCache();

        $newMenus      = [];
        $privilegeData = $AuthAccess->where(["role_id" => $roleId])->column("rule_name");//获取权限表数据

        foreach ($result as $m) {
            $newMenus[$m['id']] = $m;
        }

        foreach ($result as $n => $t) {
            $result[$n]['checked']      = ($this->_isChecked($t, $privilegeData)) ? ' checked' : '';
            $result[$n]['level']        = $this->_getLevel($t['id'], $newMenus);
            $result[$n]['style']        = empty($t['pid']) ? '' : 'display:none;';
            $result[$n]['parentIdNode'] = ($t['pid']) ? ' class="child-of-node-' . $t['pid'] . '"' : '';
        }

        $str = "<tr id='node-\$id'\$parentIdNode  style='\$style'>
                   <td style='padding-left:10px;'>\$spacer<input type='checkbox' name='menuId[]' value='\$id' level='\$level' \$checked onclick='javascript:checknode(this);'> \$name</td>
    			</tr>";
        $tree->init($result);

        $category = $tree->getTree(0, $str);

        $this->assign("category", $category);
        $this->assign("roleId", $roleId);
        //dump($category);exit();
        return $this->fetch();
    }

    /**
     * 检查指定菜单是否有权限
     * @param array $menu menu表中数组
     * @param $privData
     * @return bool
     */
    private function _isChecked($menu, $privData)
    {
        $name   = $menu['url'];
        if ($privData) {
            if (in_array($name, $privData)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    /**
     * 获取菜单深度
     * @param $id
     * @param array $array
     * @param int $i
     * @return int
     */
    protected function _getLevel($id, $array = [], $i = 0)
    {
        if ($array[$id]['pid'] == 0 || empty($array[$array[$id]['pid']]) || $array[$id]['pid'] == $id) {
            return $i;
        } else {
            $i++;
            return $this->_getLevel($array[$id]['pid'], $array, $i);
        }
    }
}