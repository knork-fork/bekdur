function leftMenu(option)
{
    if (document.getElementById("leftMenuBar") == null)
        return;

    if (option)
    {
        checkLeftMenuOption(false);
        document.getElementById("leftMenuIcon").style = "";
    }
    else
    {
        checkLeftMenuOption(true);
        document.getElementById("leftMenuIcon").style = "display:none";
    }
}

function rightMenu(option)
{
    if (document.getElementById("chatInfoMenu") == null)
        return;

    if (option)
        document.getElementById("chatInfoMenu").style = "display:none";
    else
        document.getElementById("chatInfoMenu").style = "";
}

function resize()
{
    var width = window.innerWidth;

    // for debug
    //document.getElementById("username").innerText = width+"px";

    // Show/hide left menu
    leftMenu(width < 1100);

    // Show/hide right menu
    rightMenu(width < 1300);

    // Divs (testing needed)
    if (width < 1100)
    {
        // Shrink everything
        document.getElementById("content").style = "width:auto; margin-left:0px; margin-top:50px; z-index:0;";
        
        // Todo: send message div needs fixing too
        // sendImageDiv();
        //document.getElementById("sendMessage").style = "width:auto;";
    }
    else
    {
        // Expand everything
        document.getElementById("content").style = "width:calc(100% - 720px);";

        //document.getElementById("sendMessage").style = "width:calc(100% - 600px);";
    }
};

// Initial run
resize();

// On resize event
window.onresize = function(event) 
{
    resize();
};