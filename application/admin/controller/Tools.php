<?php
namespace app\admin\controller;

use think\Db;
use think\Backup;

class Tools extends Base{

    public function index(){
        $dbtables = Db::query('SHOW TABLE STATUS');
        //dump($dbtables);exit();
        $total = 0;
        foreach ($dbtables as $k => $v) {
            $dbtables[$k]['size'] = format_bytes($v['Data_length'] + $v['Index_length']);
            $total += $v['Data_length'] + $v['Index_length'];
        }
        $this->assign('list', $dbtables);
        $this->assign('total', format_bytes($total));
        $this->assign('tableNum', count($dbtables));
        return $this->fetch();
    }

    public function export($tables = null, $id = null, $start = null){
        //防止备份数据过程超时
        function_exists('set_time_limit') && set_time_limit(0);
        if(IS_POST && !empty($tables) && is_array($tables)){ //初始化
            $path = config('DATA_BACKUP_PATH');
            if(!is_dir($path)){
                mkdir($path, 0755, true);
            }
            //读取备份配置
            $config = array(
                'path'     => realpath($path) . DIRECTORY_SEPARATOR,
                'part'     => config('DATA_BACKUP_PART_SIZE'),
                'compress' => config('DATA_BACKUP_COMPRESS'),
                'level'    => config('DATA_BACKUP_COMPRESS_LEVEL'),
            );
            //检查是否有正在执行的任务
            $lock = "{$config['path']}backup.lock";
            if(is_file($lock)){
//                $this->error('检测到有一个备份任务正在执行，请稍后再试！');
                return json(array('info'=>'检测到有一个备份任务正在执行，请稍后再试！', 'status'=>0, 'url'=>''));
            } else {
                //创建锁文件
                file_put_contents($lock, NOW_TIME);
            }

            //检查备份目录是否可写
//            is_writeable($config['path']) || $this->error('备份目录不存在或不可写，请检查后重试！');
            if(!is_writeable($config['path'])){
                return json(array('info'=>'备份目录不存在或不可写，请检查后重试！', 'status'=>0, 'url'=>''));
            }
            session('backup_config', $config);

            //生成备份文件信息
            $file = array(
                'name' => date('Ymd-His', $_SERVER['REQUEST_TIME']),
                'part' => 1,
            );
            session('backup_file', $file);
            //缓存要备份的表
            session('backup_tables', $tables);
            //创建备份文件
            $Database = new Backup($file, $config);
            if(false !== $Database->create()){
                $tab = array('id' => 0, 'start' => 0);
//                $this->success('初始化成功！', '', array('tables' => $tables, 'tab' => $tab));
                return json(array('tables' => $tables, 'tab' => $tab, 'info'=>'初始化成功！', 'status'=>1, 'url'=>''));
            } else {
//                $this->error('初始化失败，备份文件创建失败！');
                return json(array('info'=>'初始化失败，备份文件创建失败！', 'status'=>0, 'url'=>''));
            }
        } elseif (IS_GET && is_numeric($id) && is_numeric($start)) { //备份数据
            $tables = session('backup_tables');
            //备份指定表
            $Database = new Backup(session('backup_file'), session('backup_config'));
            $start  = $Database->backup($tables[$id], $start);
            if(false === $start){ //出错
//                $this->error('备份出错！');
                return json(array('info'=>'备份出错！', 'status'=>0, 'url'=>''));
            } elseif (0 === $start) { //下一表
                if(isset($tables[++$id])){
                    $tab = array('id' => $id, 'start' => 0);
//                    $this->success('备份完成！', '', array('tab' => $tab));
                    return json(array('tab' => $tab, 'info'=>'备份完成！', 'status'=>1, 'url'=>''));
                } else { //备份完成，清空缓存
                    unlink(session('backup_config.path') . 'backup.lock');
                    session('backup_tables', null);
                    session('backup_file', null);
                    session('backup_config', null);
//                    $this->success('备份完成！');
                    return json(array('info'=>'备份完成！', 'status'=>1, 'url'=>''));
                }
            } else {
                $tab  = array('id' => $id, 'start' => $start[0]);
                $rate = floor(100 * ($start[0] / $start[1]));
//                $this->success("正在备份...({$rate}%)", '', array('tab' => $tab));
                return json(array('tab' => $tab, 'info'=>"正在备份...({$rate}%)", 'status'=>1, 'url'=>''));
            }

        } else {//出错
//            $this->error('参数错误！');
            return json(array('info'=>'参数错误！', 'status'=>0, 'url'=>''));
        }
    }

    //数据还原
    public function restore(){
        $path = config('DATA_BACKUP_PATH');
        if(!is_dir($path)){
            mkdir($path, 0755, true);
        }
        $path = realpath($path);
        $flag = \FilesystemIterator::KEY_AS_FILENAME;
        $glob = new \FilesystemIterator($path,  $flag);
        $list = array();$filenum = $total = 0;
        foreach ($glob as $name => $file) {
            if(preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql(?:\.gz)?$/', $name)){
                $name = sscanf($name, '%4s%2s%2s-%2s%2s%2s-%d');
                $date = "{$name[0]}-{$name[1]}-{$name[2]}";
                $time = "{$name[3]}:{$name[4]}:{$name[5]}";
                $part = $name[6];
                $info = pathinfo($file);
                if(isset($list["{$date} {$time}"])){
                    $info = $list["{$date} {$time}"];
                    $info['part'] = max($info['part'], $part);
                    $info['size'] = $info['size'] + $file->getSize();
                } else {
                    $info['part'] = $part;
                    $info['size'] = $file->getSize();
                }
                $info['compress'] = ($info['extension'] === 'sql') ? '-' : $info['extension'];
                $info['time']  = strtotime("{$date} {$time}");
                $filenum++;
                $total += $info['size'];
                $list["{$date} {$time}"] = $info;
            }
        }
        $this->assign('list', $list);
        $this->assign('filenum',$filenum);
        $this->assign('total',$total);
        return $this->fetch();
    }

    /**
     * 执行还原数据库操作
     * @param int $time
     * @param null $part
     * @param null $start
     */
    public function import($time = 0, $part = null, $start = null){
        function_exists('set_time_limit') && set_time_limit(0);

        if(is_numeric($time) && is_null($part) && is_null($start)){ //初始化
            //获取备份文件信息
            $name  = date('Ymd-His', $time) . '-*.sql*';
            $path  = realpath(config('DATA_BACKUP_PATH')) . DIRECTORY_SEPARATOR . $name;
            $files = glob($path);
            $list  = array();
            foreach($files as $name){
                $basename = basename($name);
                $match    = sscanf($basename, '%4s%2s%2s-%2s%2s%2s-%d');
                $gz       = preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql.gz$/', $basename);
                $list[$match[6]] = array($match[6], $name, $gz);
            }
            ksort($list);

            //检测文件正确性
            $last = end($list);
            if(count($list) === $last[0]){
                session('backup_list', $list); //缓存备份列表
                $this->success('初始化完成！', null, array('part' => 1, 'start' => 0));
            } else {
                $this->error('备份文件可能已经损坏，请检查！');
            }
        } elseif(is_numeric($part) && is_numeric($start)) {
            $list  = session('backup_list');
            $db = new Backup($list[$part], array(
                'path'     => realpath(config('DATA_BACKUP_PATH')) . DIRECTORY_SEPARATOR,
                'compress' => $list[$part][2]));
            $start = $db->import($start);
            if(false === $start){
                $this->error('还原数据出错！');
            } elseif(0 === $start) { //下一卷
                if(isset($list[++$part])){
                    $data = array('part' => $part, 'start' => 0);
                    $this->success("正在还原...#{$part}", null, $data);
                } else {
                    session('backup_list', null);
                    $this->success('还原完成！');
                }
            } else {
                $data = array('part' => $part, 'start' => $start[0]);
                if($start[1]){
                    $rate = floor(100 * ($start[0] / $start[1]));
                    $this->success("正在还原...#{$part} ({$rate}%)", null, $data);
                } else {
                    $data['gz'] = 1;
                    $this->success("正在还原...#{$part}", null, $data);
                }
            }
        } else {
            $this->error('参数错误！');
        }
    }

    /**
     * 优化
     */
    public function optimize() {
        $batchFlag = input('get.batchFlag', 0, 'intval');
        //批量删除
        if ($batchFlag) {
            $table = input('key', array());
        }else {
            $table[] = input('tablename' , '');
        }

        if (empty($table)) {
            $this->error('请选择要优化的表');
        }

        $strTable = implode(',', $table);
        if (!DB::query("OPTIMIZE TABLE {$strTable} ")) {
            $strTable = '';
        }
        $this->success("优化表成功" . $strTable, url('Tools/index'));

    }

    /**
     * 修复
     */
    public function repair() {
        $batchFlag = input('get.batchFlag', 0, 'intval');
        //批量删除
        if ($batchFlag) {
            $table = input('key', array());
        }else {
            $table[] = input('tablename' , '');
        }

        if (empty($table)) {
            $this->error('请选择修复的表');
        }

        $strTable = implode(',', $table);
        if (!DB::query("REPAIR TABLE {$strTable} ")) {
            $strTable = '';
        }

        $this->success("修复表成功" . $strTable, url('Tools/index'));

    }


    public function restoreUpload()
    {
        $file = request()->file('sqlfile');
        if(empty($file)){
            $this->error('请先上传sql文件');
        }
        // 移动到框架应用根目录/public/uploads/ 目录下
        $path = ROOT_PATH . 'public/upload/sqldata' ;
        $info = $file->validate(['size'=>200000000,'ext'=>'sql,gz'])->move($path, $_FILES['sqlfile']['name']);
        if ($info) {
            //上传成功 获取上传文件信息
            $file_path_full = $info->getPathName();
            if (file_exists($file_path_full)) {
                $sqls = Backup::parseSql($file_path_full);
                if(Backup::install($sqls)){
//                    array_map("unlink", glob($path));
                    $this->success("导入sql文件成功", url('Tools/restore'));
                }else{
                    $this->error('sql文件导入失败');
                }
            } else {
                $this->error('sql文件上传失败');
            }
        } else {
            //上传错误提示错误信息
            $this->error($file->getError());
        }
    }

    /**
     * 下载
     * @param int $time
     */
    public function downFile($time = 0) {
        $name  = date('Ymd-His', $time) . '-*.sql*';
        $path  = realpath(config('DATA_BACKUP_PATH')) . DIRECTORY_SEPARATOR . $name;
        $files = glob($path);
        if(is_array($files)){
            foreach ($files as $filePath){
                if (!file_exists($filePath)) {
                    $this->error("该文件不存在，可能是被删除");
                }else{
                    $filename = basename($filePath);
                    header("Content-type: application/octet-stream");
                    header('Content-Disposition: attachment; filename="' . $filename . '"');
                    header("Content-Length: " . filesize($filePath));
                    readfile($filePath);
                }
            }
        }
    }


    /**
     * 删除备份文件
     * @param  Integer $time 备份时间
     */
    public function del($time = 0){
        if($time){
            $name  = date('Ymd-His', $time) . '-*.sql*';
            $path  = realpath(config('DATA_BACKUP_PATH')) . DIRECTORY_SEPARATOR . $name;
            array_map("unlink", glob($path));
            if(count(glob($path))){
                $this->error('备份文件删除失败，请检查权限！');
            } else {
                $this->success('备份文件删除成功！');
            }
        } else {
            $this->error('参数错误！');
        }
    }






}