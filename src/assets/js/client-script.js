(function($){

	const containerPlayes = document.getElementById('container-players');
	const playBtn = document.getElementById('ail-play-speaker');
	const audio = document.getElementById('audio-speaker');
	const backTen = document.getElementById('ail-back-speaker');
	const forwardTen = document.getElementById('ail-forward-speaker');

    playBtn.addEventListener('click', function(){
		const isPlaying = containerPlayes.classList.contains('is-playing');
		playOrPauseSpeaker( !!isPlaying );
	});

	backTen.addEventListener('click', function(){
		audio.currentTime -= 10;
	});

	audio.addEventListener('ended', function(){
		console.log('finished');
		pauseSpeaker();
	})

	forwardTen.addEventListener('click', function(){
		audio.currentTime += 10;
	});

	function playOrPauseSpeaker( isPlaying ){
		if( isPlaying === true ){
			pauseSpeaker();
		} else {
			playSpeaker();
		}
	}

	function pauseSpeaker() {
		containerPlayes.classList.remove('is-playing');
		containerPlayes.querySelector('i#ail-play-speaker').classList.remove('fa-pause');
		containerPlayes.querySelector('i#ail-play-speaker').classList.add('fa-play');
		audio.pause();
	}

	function playSpeaker() {
		containerPlayes.classList.add('is-playing');
		containerPlayes.querySelector('i#ail-play-speaker').classList.remove('fa-play');
		containerPlayes.querySelector('i#ail-play-speaker').classList.add('fa-pause');
		audio.play();
	}

}(jQuery));
