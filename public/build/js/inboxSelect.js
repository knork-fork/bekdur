function enterInbox(user_id)
{
    // to-do: find a way to do this without refreshing the whole page

    // Enter user inbox and create it if it doesn't exist
    location.href = "/dashboard/inbox/user/" + user_id;
}

function selectInbox(id)
{
    // to-do: find a way to do this without refreshing the whole page
    
    // Enter the user inbox itself
    location.href = "/dashboard/inbox/" + id;
}