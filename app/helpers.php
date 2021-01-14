<?php

/**
 * 加载静态资源
 * @param $path 资源路径
 * @return string 返回资源路径全名
 */
function asset_path($path)
{
    return env('ASSET_PATH') . DIRECTORY_SEPARATOR . $path;
}

/**
 * 存储字节转换为可视化单位
 * @param $bytes
 * @param int $decimals
 * @return string
 */
function human_filesize($bytes, $decimals = 2)
{
    $size = ['B', 'kB', 'MB', 'GB', 'TB', 'PB'];
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
}

/**
 * api返回格式（json）
 * @param $message
 * @param $data
 * @param int $status
 * @return \Illuminate\Http\JsonResponse
 */
function api($status = 201,$message='success',$data = [])
{
    $res = [
        'status' => $status,
        'message' => $message,
        'data' => $data
    ];
    return response()->json($res, $status);
}

/**
 * get请求 封装函数
 * @param $url
 * @return mixed
 */
function geturl($url)
{
    $headerArray = array("Content-type:application/json;", "Accept:application/json");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
//    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);
    $output = curl_exec($ch);
    curl_close($ch);
    $output = json_decode($output, true);
    return $output;
}

/**
 * post请求 封装函数
 * @param $url
 * @param $data
 * @return mixed
 */
function posturl($url, $data)
{
    $data = json_encode($data);
    $headerArray = array("Content-type:application/json;charset='utf-8'", "Accept:application/json");
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
//    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArray);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return json_decode($output, true);
}
