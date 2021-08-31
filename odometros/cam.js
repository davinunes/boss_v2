(function () {
  if ( !"mediaDevices" in navigator || !"getUserMedia" in navigator.mediaDevices ){
    M.toast({html: 'Não tem camera!!!', classes: 'rounded'});
    return;
  }

  // get page elements
  const video = document.querySelector("#video");
  const btnPlay = document.querySelector("#btnPlay");
  const Salvar = document.querySelector("#btnScreenshot");
  const btnPause = document.querySelector("#btnPause");
  const btnScreenshot = document.querySelector("#enviar");
  const btnChangeCamera = document.querySelector("#btnChangeCamera");
  const screenshotsContainer = document.querySelector("#screenshots");
  const canvas = document.querySelector("#canvas");
  const devicesSelect = document.querySelector("#devicesSelect");


  // video constraints
  const constraints = {
    video: {
      width: {
        min: 1280,
        ideal: 1920,
        max: 2560,
      },
      height: {
        min: 720,
        ideal: 1080,
        max: 1440,
      },
    },
  };

  // use front face camera
  let useFrontCamera = false;

  // current video stream
  let videoStream;

  // handle events
  // play
  btnPlay.addEventListener("click", function () {
    video.play();
    btnPlay.classList.add("hide");
    btnPause.classList.remove("hide");
  });

  // pause
  btnPause.addEventListener("click", function () {
    video.pause();
	
	// Redimensionamento da imagem
	width = video.videoWidth,
	height = video.videoHeight;
	tamanho = 700;
		if (width > height) {
			if (width > tamanho) {
				height *= tamanho / width;
				width = tamanho;
			}
		} else {
			if (height > tamanho) {
				width *= tamanho / height;
				height = tamanho;
			}
		}
	canvas.width = width;
    canvas.height = height;
    canvas.getContext("2d").drawImage(video, 0, 0, width, height);
	
	$("#enviar").attr("onepause","1");
	
    btnPause.classList.add("hide");
    btnPlay.classList.remove("hide");
  });

  // take screenshot
  btnScreenshot.addEventListener("click", function () {


  });

  // switch camera
  btnChangeCamera.addEventListener("click", function () {
    useFrontCamera = !useFrontCamera;

    initializeCamera();
  });

  // stop video stream
  function stopVideoStream() {
    if (videoStream) {
      videoStream.getTracks().forEach((track) => {
        track.stop();
      });
    }
  }

  // initialize
  async function initializeCamera() {
    stopVideoStream();
    constraints.video.facingMode = useFrontCamera ? "user" : "environment";

    try {
      videoStream = await navigator.mediaDevices.getUserMedia(constraints);
      video.srcObject = videoStream;
    } catch (err) {
      M.toast({html: 'Não tem camera!!!', classes: 'rounded'});
    }
  }
  
initializeCamera();


})();