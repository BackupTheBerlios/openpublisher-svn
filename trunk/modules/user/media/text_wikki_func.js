function insertFile(folder,file,id_file)
{
    smartAddText('[data/user/'+folder+'/'+file+' '+file+']','',document.edituser.description);
}
function insertFileDesc(desc)
{
    smartAddText(desc,'',document.edituser.description);
}
function insertImage(folder,file,id_pic)
{
    smartAddText('[[image data/user/'+folder+'/thumb/'+file+' link="?view=picture&id_pic='+id_pic+'" align="left" hspace="4"]]','',document.edituser.description);
}
function insertImgDesc(desc)
{
    smartAddText(desc,'',document.edituser.description);
}
