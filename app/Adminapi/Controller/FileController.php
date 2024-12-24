<?php

namespace App\Adminapi\Controller;

use App\Adminapi\Lists\File\FileCateLists;
use App\Adminapi\Lists\File\FileLists;
use App\Adminapi\Logic\FileLogic;
use App\Adminapi\Validate\FileValidate;

/**文件管理
 */
class FileController extends BaseAdminController
{

    /**
     * @notes 文件列表
     */
    public function lists()
    {
        return $this->dataLists(new FileLists());
    }


    /**
     * @notes 文件移动成功
     */
    public function move()
    {
        $params = (new FileValidate())->post()->goCheck('move');
        FileLogic::move($params);
        return $this->success('移动成功', [], 1, 1);
    }


    /**
     * @notes 重命名文件
     */
    public function rename()
    {
        $params = (new FileValidate())->post()->goCheck('rename');
        FileLogic::rename($params);
        return $this->success('重命名成功', [], 1, 1);
    }


    /**
     * @notes 删除文件
     */
    public function delete()
    {
        $params = (new FileValidate())->post()->goCheck('delete');
        FileLogic::delete($params);
        return $this->success('删除成功', [], 1, 1);
    }


    /**
     * @notes 分类列表
     */
    public function listCate()
    {
        return $this->dataLists(new FileCateLists());
    }


    /**
     * @notes 添加文件分类
     */
    public function addCate()
    {
        $params = (new FileValidate())->post()->goCheck('addCate');
        FileLogic::addCate($params);
        return $this->success('添加成功', [], 1, 1);
    }


    /**
     * @notes 编辑文件分类
     */
    public function editCate()
    {
        $params = (new FileValidate())->post()->goCheck('editCate');
        FileLogic::editCate($params);
        return $this->success('编辑成功', [], 1, 1);
    }


    /**
     * @notes 删除文件分类
     */
    public function delCate()
    {
        $params = (new FileValidate())->post()->goCheck('id');
        FileLogic::delCate($params);
        return $this->success('删除成功', [], 1, 1);
    }
}
