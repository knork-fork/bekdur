var leftMenuShown = false;

function checkLeftMenuOption(forced)
{
    if (leftMenuShown || forced)
        document.getElementById("leftMenuBar").style = "z-index:1;";
    else
        document.getElementById("leftMenuBar").style = "display:none";
}

function leftMenuSwitch()
{
    if (leftMenuShown)
    {
        leftMenuShown = false;
        checkLeftMenuOption();
    }
    else
    {
        leftMenuShown = true;
        checkLeftMenuOption();
    }
}