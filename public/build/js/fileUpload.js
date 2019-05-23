function imageUpload()
{
    document.getElementById("fileInputLabel").style = "color:gray;";
    document.getElementById("image").disabled = false;
    document.getElementById("file").disabled = true;
}

function fileUpload()
{
    document.getElementById("imageInputLabel").style = "color:gray;";
    document.getElementById("image").disabled = true;
    document.getElementById("file").disabled = false;
}