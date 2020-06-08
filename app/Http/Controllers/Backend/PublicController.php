<?php
/**
 * Created by PhpStorm.
 * User: sogubaby
 * Date: 2020/6/8
 * Time: 上午11:26
 */

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Endroid\QrCode\QrCode;

class PublicController extends Controller
{
    public function getQrCode()
    {
        $qrCode = new QrCode();
        $qrCode->setText('https://www.baidu.com/'); //设置二维码上的内容

        $qrCode->setSize(300);  //二维码尺寸

        $qrCode->setWriterByName('png'); //设置输出的二维码图片格式

        $qrCode->setMargin(10);

        $qrCode->setEncoding('UTF-8');

        //保存二维码
        if (!is_dir(public_path( '/qrcode'))) {
            mkdir(public_path( '/qrcode'));
        }
        $qrCode->writeFile(public_path( '/qrcode/qrcode.png'));
    }
}