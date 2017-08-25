function deletebox(onoklink,reference)
{
	var answer = confirm("Weet je zeker dat je " + reference + " wilt verwijderen?");
	if (answer)
		window.location = onoklink;
}

function paydatebox(ticketnumber)
{
	var answer = prompt("Wat is de betalingsdatum?","");
	if (answer)
		window.location = "./regadmin.php?mode=pay&ticketnumber=" + ticketnumber + "&date=" + answer;
}
