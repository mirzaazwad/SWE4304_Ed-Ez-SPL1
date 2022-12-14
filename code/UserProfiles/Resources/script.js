if (window.history.replaceState) {
  window.history.replaceState(null, null, window.location.href);
}

$(document).ready(function(){
	$('.search-box input[type="search"]').on("keyup input", function(){
			/* Get input value on change */
			var inputVal = $(this).val();
			var resultDropdown = $(this).siblings(".result");
			var element=document.getElementById("uploadsave");
			if(inputVal.length){
				element.style.display="none";
					$.get("search.php", {term: inputVal}).done(function(data){
							// Display the returned data in browser
							resultDropdown.html(data);
					});
			} else{
				element.style.display=null;
					resultDropdown.empty();
			}
	});
	
	// Set search input value on click of result item
	$(document).on("click", ".result button", function(){
			$(this).parents(".search-box").find('input[type="search"]').val($(this).text());
			$(this).parent(".result").empty();
	});
});

// JavaScript code
function search_resources() {
	let input = document.getElementById('searchbar').value
	input=input.toLowerCase();
	let x = document.getElementsByClassName('saved-resources');
	let y = document.getElementsByClassName('saved');
	
	for (i = 0; i < x.length; i++) {
			if (!x[i].innerHTML.toLowerCase().includes(input)) {
					x[i].style.display="none";
					y[i].style.display="none";
			}
			else {
					x[i].style.display="block";	
					y[i].style.display="block";			
			}
	}
}

function search_uploaded_resources() {
	let input = document.getElementById('searchbar-uploaded').value
	input=input.toLowerCase();
	let x = document.getElementsByClassName('uploaded-resources');
	let y = document.getElementsByClassName('uploaded');
	for (i = 0; i < x.length; i++) {
		if (!x[i].innerHTML.toLowerCase().includes(input)) {
				x[i].style.display="none";
				y[i].style.display="none";
		}
		else {
				x[i].style.display="block";	
				y[i].style.display="block";			
		}
}
}

const selectElement = document.querySelector('#classroom');
const optionElements = selectElement.querySelectorAll('option');

optionElements.forEach(option => {
  option.addEventListener('click', () => {
    option.selected = !option.selected;
  });
});

const radioButton = document.querySelector('#privateResource');
const radioButton2 = document.querySelector('#publicResource');
const dropdownMenu = document.querySelector('#classroom');

radioButton.addEventListener('click', () => {
  if(radioButton.checked) {
    dropdownMenu.style.display = 'block';
  } else {
    dropdownMenu.style.display = 'none';
  }
	radioButton2.checked = !radioButton.checked;
});

radioButton2.addEventListener('click', () => {
	if(radioButton2.checked) {
	  dropdownMenu.style.display = 'none';
	}
	radioButton.checked = !radioButton2.checked;
  });

	function validation_check(){
		var y=document.getElementById('error');
		var z=document.getElementById('display_error');
		var a=document.getElementById('classroom').value;
		if(a==="$" && radioButton.checked){
			y.style.display="block";
			z.innerHTML="No fields can be empty";
			return false;
		}
		return true;
	}
	
	const form=document.getElementById('form');
	form.addEventListener('submit', (e) => {
		if(validation_check()===false){
			e.preventDefault();
		}
	})