$(document).ready(function() {
	//refreshMenuLoop();
});

function refreshMenuLoop()
{
	// Set refresh interval
	window.refreshInterval = 150; // Seconds
	// Do initial worker info fetch/display, start initial countdown
	refreshMenuAjax();
	resetRefreshCountdown();
	// Start worker refresh loop
	window.refreshWorkerInterval = setInterval(function(){
		refreshMenuAjax();
		resetRefreshCountdown();
	}, window.refreshInterval*1000);
}

function refreshMenuAjax()
{
	$.post("/", { 
			c: 'menu', 
			a: 'refreshmenu', 
			ajax: '1'
		}, function(data) {
			$('#menuContainer').html(data);
			$('.menuItemPopup').popup();
	});
}

function resetRefreshCountdown()
{
	// Clear existing previous counter
	clearInterval(window.refreshCounterInterval);
	// Set countdown var
	window.refreshCounter = window.refreshInterval;
	// Display number of seconds until refresh
	//$('#refreshCount').html(window.refreshCounter);
	// Execute once a second
	window.refreshCounterInterval = setInterval(function(){
		// Deal with counter values less than one
		if (window.refreshCounter < 1) {
			// Clear existing previous counter
			clearInterval(window.refreshCounterInterval);
			// Reset to original interval
			window.refreshCounter = 0;
		} else {
			// otherwise decrement
			window.refreshCounter--;
			// Print adjusted count
			//$('#refreshCount').html(window.refreshCounter);
		}
	}, 1000);
}