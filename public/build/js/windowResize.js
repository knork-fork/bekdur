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

function sendImageDiv(option)
{
    if (document.getElementById("sendMessage") == null)
        return;

    var contentWidth = document.getElementById("content").offsetWidth - 2*75;

    if (option)
        document.getElementById("sendMessage").style = "width:" + contentWidth + "px; left:75px; margin-left:0px; margin-top:50px; z-index:0;";
    else
        document.getElementById("sendMessage").style = "width:calc(100% - 603px);";
}

function resize()
{
    var width = window.innerWidth;

    // for debug
    /*if (document.getElementById("username") != null)
        document.getElementById("username").innerText = width+"px";*/
        
    // Show/hide left menu
    leftMenu(width < 1100);

    // Show/hide right menu
    rightMenu(width < 1300);

    if (document.getElementById("content") == null)
        return;

    // Divs (testing needed)
    if (width < 1100)
    {
        // Shrink everything
        document.getElementById("content").style = "width:auto; margin-left:0px; margin-top:50px; z-index:0;";
        
        sendImageDiv(true);
    }
    else
    {
        // Expand everything
        document.getElementById("content").style = "width:calc(100% - 720px);";

        sendImageDiv(false);
    }
};

// Initial run
resize();

// On resize event
window.onresize = function(event) 
{
    resize();
};

window.onload = function(event) 
{
    resize();
};