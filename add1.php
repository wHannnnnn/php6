<?php
function addmusic (){
  if(empty($_POST['title'])){
    $GLOBALS['message']='请输入标题';
    return;
  }
  if(empty($_POST['artist'])){
    $GLOBALS['message']='请输入歌手名';
    return;
  }

  $image=$_FILES['images'];

  if($image['error']!==UPLOAD_ERR_OK){  //是否上传
    $GLOBALS['message']='请选择图片';
    return;
  }

  $types=array('image/jpg', 'image/png', 'image/jpeg');
  if(!in_array($image['type'], $types)){
    $GLOBALS['message']='请选择格式正确的图片';
    return;
  }

  if(2*1024*1024<$image['size']) {
    $GLOBALS['message']='文件大小不正确,请上传小于2M的图片';
    return;
  }


  $target='./uploads/images/' . uniqid() . $image['name'];
  $images=substr($target, 1);

  if(!move_uploaded_file( $image['tmp_name'] , $target)){  //是否上传成功
  $GLOBALS['message'] = '上传图片失败';
  return;
  }



  $source=$_FILES['source'];

  if($source['error']!==UPLOAD_ERR_OK){  //是否上传
    $GLOBALS['message']='请选择音频文件';
    return;
  }

  $sourcetypes=array('audio/mp3', 'audio/wma');
  if(!in_array($source['type'], $sourcetypes)){
    $GLOBALS['message']='请选择格式正确的音频文件';
    return;
  }

  if(20*1024*1024<$source['size']) {
    $GLOBALS['message']='文件大小不正确,请上传小于20M的音频文件';
    return;
  }


  $target1='./uploads/audio/' . uniqid() . $source['name'];
    $sources=substr($target1, 1);

  if(!move_uploaded_file( $source['tmp_name'] , $target1)){  //是否上传成功
  $GLOBALS['message'] = '上传音频文件失败';
  return;
  }



  $neworigin=array(
    'id' => uniqid(),
    'title' => $_POST['title'],
    'artist' => $_POST['artist'],
    'images' => $images,
    'source' => $sources

  );//获取新数据
  $origin=json_decode(file_get_contents('storage.json'),true);
  //获取已有数据 键值对

  $origin[]=$neworigin; //追加新的数据
  file_put_contents('storage.json', json_encode($origin)); //将文件重新写入到json中

  header('Location: ./list.php');


} 
if($_SERVER['REQUEST_METHOD']==='POST'){
  addmusic();
}



 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>添加新音乐</title>
  <link rel="stylesheet" href="bootstrap.css">
</head>
<body>
  <div class="container py-5">
    <h1 class="display-4">添加新音乐</h1>
    <hr>
    <?php if (isset($message)): ?>
    <div class="alert alert-danger" role="alert">
      <?php echo $message; ?>
    </div>
    <?php endif ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" autocomplete="off">
      <div class="form-group">
        <label for="title">标题</label>
        <input type="text" class="form-control" id="title" name="title">
      </div>
      <div class="form-group">
        <label for="artist">歌手</label>
        <input type="text" class="form-control" id="artist" name="artist">
      </div>
      <div class="form-group">
        <label for="images">海报</label>
        <input type="file" class="form-control" id="images" name="images">
      </div>
      <div class="form-group">
        <label for="source">音乐</label>
        <!-- accept 可以限制文件域能够选择的文件种类，值是 MIME Type -->
        <input type="file" class="form-control" id="source" name="source" accept="audio/*">
      </div>
      <button class="btn btn-primary btn-block">保存</button>
    </form>
  </div>
</body>
</html>