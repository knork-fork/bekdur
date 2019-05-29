var formShown = false;

function showNewGroupForm()
{
    if (formShown)
    {
        // Submit form
        document.getElementById("newGroupForm").submit(); 
    }
    else
    {
        // Remove display:none for new group form
        document.getElementById("newGroupForm").style = "";

        formShown = true;
    }
}

function profileUpload()
{
    document.getElementById("profileImgLabel").style = "color:green;";
}

function backgroundUpload()
{
    document.getElementById("backgroundImgLabel").style = "color:green;";
}