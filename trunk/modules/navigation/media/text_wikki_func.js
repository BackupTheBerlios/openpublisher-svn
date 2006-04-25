function insertFile(folder,file,id_file)
{
    smartAddText('[data/navigation/'+folder+'/'+file+' '+file+']','',document.editnode.body);
}
function insertFileDesc(desc)
{
    smartAddText(desc,'',document.editnode.body);
}
function insertImage(controller,path,file,title,id_pic,width,height)
{
    smartAddText('[[image '+path+file+' link="?view=picture&id_pic='+id_pic+'" align="left" hspace="4"]]','',document.editnode.body);
}
function insertImgDesc(desc)
{
    smartAddText(desc,'',document.editnode.body);
}
