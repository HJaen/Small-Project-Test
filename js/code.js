const urlBase = '/LAMPAPI';
const extension = 'php';

const deleteBtns = document.querySelectorAll(".deleteButton");
const table = document.querySelector(".contactTable");
let userId = 0;
let firstName = "";
let lastName = "";

for (let x = 0; x < deleteBtns.length; x++)
{
	deleteBtns[x].addEventListener("click", () => {
		deleteContact(deleteBtns[x]);
	});
}

function CheckFields()
{
	let firstName = document.getElementById("FirstName").value;
	let lastName = document.getElementById("LastName").value;
	let login = document.getElementById("loginName").value;
	let password = document.getElementById("loginPassword").value;
	let reEnterPass = document.getElementById("loginPasswordreenter").value;

	if(firstName == ""){
		return [false, "Please enter First Name"];
	}
	else if(lastName == ""){
		return [false, "Please enter Last Name"];
	}
	else if(login == ""){
		return [false, "Please enter Username"];
	}
	else if(password == ""){
		return [false, "Please enter Password"];
	}
	else if(password != reEnterPass){
		return [false, "Passwords do not match."];
	}
	else
	return [true,""];
}

function doRegister()
{
	if(!CheckFields()[0])
	{
		document.getElementById("loginResult").innerHTML = CheckFields()[1];
	}
	else{
		userId = 0;
		let firstName = document.getElementById("FirstName").value;
		let lastName = document.getElementById("LastName").value;
		
		let login = document.getElementById("loginName").value;
		let password = document.getElementById("loginPassword").value;
		var hash = md5( password );
		
		document.getElementById("loginResult").innerHTML = "";

		let tmp = {firstName:firstName,LastName:lastName,Login:login,Password:hash};
	//	var tmp = {login:login,password:hash};
		let jsonPayload = JSON.stringify( tmp );
		
		let url = urlBase + '/CreateUser.' + extension;

		let xhr = new XMLHttpRequest();
		xhr.open("POST", url, true);
		xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
		try
		{
			xhr.onreadystatechange = function() 
			{
				if (this.readyState == 4 && this.status == 200) 
				{
					let jsonObject = JSON.parse( xhr.responseText );
					window.location.href = "index.html";
				}
			};
			xhr.send(jsonPayload);
		}
		catch(err)
		{
			document.getElementById("loginResult").innerHTML = err.message;
		}
	}
}

function doLogin()
{
	userId = 0;
	firstName = "";
	lastName = "";
	
	let login = document.getElementById("loginName").value;
	let password = document.getElementById("loginPassword").value;
	var hash = md5( password );
	
	document.getElementById("loginResult").innerHTML = "";

	let tmp = {login:login,password:hash};
//	var tmp = {login:login,password:hash};
	let jsonPayload = JSON.stringify( tmp );
	
	let url = urlBase + '/Login.' + extension;

	let xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				let jsonObject = JSON.parse( xhr.responseText );
				userId = jsonObject.id;
		
				if( userId < 1 )
				{		
					document.getElementById("loginResult").innerHTML = "User/Password combination incorrect";
					return;
				}
		
				firstName = jsonObject.firstName;
				lastName = jsonObject.lastName;

				saveCookie();
	
				window.location.href = "contact.html";
			}
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("loginResult").innerHTML = err.message;
	}

}

function saveCookie()
{
	let minutes = 20;
	let date = new Date();
	date.setTime(date.getTime()+(minutes*60*1000));	
	document.cookie = "firstName=" + firstName + ",lastName=" + lastName + ",userId=" + userId + ";expires=" + date.toGMTString();
}

function readCookie()
{
	userId = -1;
	let data = document.cookie;
	let splits = data.split(",");
	for(var i = 0; i < splits.length; i++) 
	{
		let thisOne = splits[i].trim();
		let tokens = thisOne.split("=");
		if( tokens[0] == "firstName" )
		{
			firstName = tokens[1];
		}
		else if( tokens[0] == "lastName" )
		{
			lastName = tokens[1];
		}
		else if( tokens[0] == "userId" )
		{
			userId = parseInt( tokens[1].trim() );
		}
	}
	
	if( userId < 0 )
	{
		window.location.href = "index.html";
	}
	else
	{
		document.getElementById("userName").innerHTML = "Welcome " + firstName + "!";
	}
}

function doLogout()
{
	userId = 0;
	firstName = "";
	lastName = "";
	document.cookie = "firstName= ; expires = Thu, 01 Jan 1970 00:00:00 GMT";
	window.location.href = "index.html";
}

function addColor()
{
	let newColor = document.getElementById("colorText").value;
	document.getElementById("colorAddResult").innerHTML = "";

	let tmp = {color:newColor,userId,userId};
	let jsonPayload = JSON.stringify( tmp );

	let url = urlBase + '/AddColor.' + extension;
	
	let xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				document.getElementById("colorAddResult").innerHTML = "Color has been added";
			}
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("colorAddResult").innerHTML = err.message;
	}
	
}

function searchColor()
{
	let srch = document.getElementById("searchText").value;
	document.getElementById("colorSearchResult").innerHTML = "";
	
	let colorList = "";

	let tmp = {search:srch,userId:userId};
	let jsonPayload = JSON.stringify( tmp );

	let url = urlBase + '/SearchColors.' + extension;
	
	let xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				document.getElementById("colorSearchResult").innerHTML = "Color(s) has been retrieved";
				let jsonObject = JSON.parse( xhr.responseText );
				
				for( let i=0; i<jsonObject.results.length; i++ )
				{
					colorList += jsonObject.results[i];
					if( i < jsonObject.results.length - 1 )
					{
						colorList += "<br />\r\n";
					}
				}
				
				document.getElementsByTagName("p")[0].innerHTML = colorList;
			}
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("colorSearchResult").innerHTML = err.message;
	}
	
}

// Add contact to datebase 
function confirmAddButton()
{
	let firstName = document.getElementById("addFirstName").value;
	let lastName = document.getElementById("addLastName").value;
	let email = document.getElementById("addEmail").value;
	let phone = document.getElementById("addPhoneNumber").value;

	document.getElementById("contactAddResult").innerHTML = "";

	let tmp = {firstName:firstName,lastName:lastName,email:email,phone:phone,userID:userId,dateCreated:dateCreated};
	let jsonPayload = JSON.stringify( tmp );

	let url = urlBase + '/AddContact.' + extension;

	let xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "applciation/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200)
			{
				document.getElementById("contactAddResult").innerHTML = "Contact added!";
			}
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("contactAddResult").innerHTML = err.message;
	}
}

function deleteContact(tableRowBtn)
{
	let row = tableRowBtn.parentNode;

	// Find the corresponding row to the button
	while ( row && row.id == 'undefined')
	{
		row = row.parentNode;
	}

	let contactID = row.id;
	let tmp = {ID:contactID};
	let jsonPayload = JSON.stringify( tmp );
	let url = urlBase + '/DeleteContact.' + extension;
	let xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

	try
	{
		xhr.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				table.deleteRow(tableRow.rowIndex);
			}
		};
		xhr.send(jsonPayload);

	}
	catch(err)
	{
		// Add error somewhere?
		console.log(err);
	}
}

function searchContacts()
{
	let search = document.getElementById("searchText").value;
	table.innerHTML = "";
	table.innerHTML += "<thead>\n" +
							"\t<tr>\n" +
								"\t\t<th>First Name</th>\n" +
								"\t\t<th>Last Name</th>\n" +
								"\t\t<th>Email</th>\n" +
								"\t\t<th>Phone Number</th>\n" +
								"\t\t<th>Date Created</th>\n" +
								"\t\t<th class='col-actions'>Actions</th>\n" +
							"\t</tr>" +
						"</thead>";

	let contactsList = "";

	let tmp = {search:search, ID:userId};
	let jsonPayload = JSON.stringify( tmp );

	let url = urlBase + '/SearchColors.' + extension;

	let xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				let jsonObject = JSON.parse( xhr.responseText );

				// Add new info to the table
				Object.keys(jsonObject).forEach(key => {
					let tableRow = document.createElement("tr");
					let contactID = jsonObject.Contacts.key;
					let firstName = jsonObject.Contacs.key.FirstName;
					let lastName = jsonObject.Contacs.key.LastName;
					let email = jsonObject.Contacs.key.Email;
					let phone = jsonObject.Contacs.key.PhoneNumber;
					tableRow.id = contactID;
					tableRow.innerHTML += "<td>" + firstName + "</td>\n";
					tableRow.innerHTML += "<td>" + lastName + "</td>\n";
					tableRow.innerHTML += "<td>" + email + "</td>\n";
					tableRow.innerHTML += "<td>" + phone + "</td>\n";
					tableRow.innerHTML += "<td>" + dateCreated + "</td>\n";
					table.innerHTML += tableRow;
				});
			}
		}

		xhr.send( jsonPayload );
	}
	catch (err)
	{
		console.log(err);
	}
}