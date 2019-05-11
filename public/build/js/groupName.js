function showGroupName(groupName, groupId)
{
    var groupElement = document.getElementById("group_" + groupId);
    var labelElement = document.getElementById("groupName");

    var rect = groupElement.getBoundingClientRect();
    var labelHeight = rect.top - 40;

    labelElement.innerText = groupName;
    labelElement.style="display:flex;top:" + labelHeight + "px;";
}

function hideGroupName()
{
    var labelElement = document.getElementById("groupName");

    labelElement.style="display:none;";
    labelElement.innerText = "";
}