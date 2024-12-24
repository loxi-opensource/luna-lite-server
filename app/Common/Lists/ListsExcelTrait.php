<?php

namespace App\Common\Lists;

use App\Common\Cache\ExportCache;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

trait ListsExcelTrait
{
    public int $pageStart = 1; //导出开始页码
    public int $pageEnd = 200; //导出介绍页码
    public string $fileName = ''; //文件名称

    /**
     * @notes 创建excel
     * @param $excelFields
     * @param $lists
     */
    public function createExcel($excelFields, $lists)
    {
        $title = array_values($excelFields);

        $data = [];
        foreach ($lists as $row) {
            $temp = [];
            foreach ($excelFields as $key => $excelField) {
                $fieldData = $row[$key];
                if (is_numeric($fieldData) && strlen($fieldData) >= 12) {
                    $fieldData .= "\t";
                }
                $temp[$key] = $fieldData;
            }
            $data[] = $temp;
        }
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        //设置单元格内容
        foreach ($title as $key => $value) {
            // 单元格内容写入
            $sheet->setCellValue([$key + 1, 1], $value);
        }
        $row = 2; //从第二行开始
        foreach ($data as $item) {
            $column = 1;
            foreach ($item as $value) {
                //单元格内容写入
                $sheet->setCellValue([$column, $row], $value);
                $column++;
            }
            $row++;
        }

        $getHighestRowAndColumn = $sheet->getHighestRowAndColumn();
        $HighestRow = $getHighestRowAndColumn['row'];
        $column = $getHighestRowAndColumn['column'];
        $titleScope = 'A1:' . $column . '1';//第一（标题）范围（例：A1:D1)

        $sheet->getStyle($titleScope)
            ->getFill()
            ->setFillType(Fill::FILL_SOLID) // 设置填充样式
            ->getStartColor()
            ->setARGB('00B0F0');
        // 设置文字颜色为白色
        $sheet->getStyle($titleScope)->getFont()->getColor()
            ->setARGB('FFFFFF');

//        $sheet->getStyle('B2')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

        $allCope = 'A1:' . $column . $HighestRow;//整个表格范围（例：A1:D5）
        $sheet->getStyle($allCope)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        //创建excel文件
        $exportCache = new ExportCache();
        $src = $exportCache->getSrc();

        if (!file_exists($src)) {
            mkdir($src, 0775, true);
        }
        $writer->save($src . $this->fileName);
        //设置本地excel缓存并返回下载地址
        $vars = ['file' => $exportCache->setFile($this->fileName)];
        return url()->query('adminapi/download/export', $vars);
    }

    /**
     * @notes 获取导出信息
     * @return array
     */
    public function excelInfo()
    {
        $count = $this->count();
        $sum_page = max(ceil($count / $this->pageSize), 1);
        return [
            'count' => $count, //所有数据记录数
            'page_size' => $this->pageSize,//每页记录数
            'sum_page' => $sum_page,//一共多少页
            'max_page' => floor($this->pageSizeMax / $this->pageSize),//最多导出多少页
            'all_max_size' => $this->pageSizeMax,//最多导出记录数
            'page_start' => $this->pageStart,//导出范围页码开始值
            'page_end' => min($sum_page, $this->pageEnd),//导出范围页码结束值
            'file_name' => $this->fileName,//默认文件名
        ];
    }
}
