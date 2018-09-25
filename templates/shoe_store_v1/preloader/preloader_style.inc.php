<?php 
$preloader_color = $this->params->get('preloader_color');
$preloader_bg_color = $this->params->get('preloader_overlay_bg_color');
 ?>



<style>

#preloader {
  position: fixed;
  top:0;
  left:0;
  right:0;
  bottom: 0;
  z-index: 999999;
  width:100%;
  height: 100%;
  background-color: <?php echo $preloader_bg_color; ?>;


  <?php if 
	($this->params->get('preloader_custom_bg_file')==""):
	?>

	<?php else: ?>

    background-image: url("<?= $this->baseurl ?>/<?= $this->params->get('preloader_custom_bg_file')?>");
	background-size: <?= $this->params->get('preloader_bg_size')?>;
	background-repeat: <?= $this->params->get('preloader_bg_repeat')?> ;
	background-attachment: <?= $this->params->get('preloader_bg_attachment')?> ;
	-ms-background-position-x:<?= $this->params->get('preloader_bg_hr_position')?> ;
	background-position-x: <?= $this->params->get('preloader_bg_hr_position')?>;
	-ms-background-position-y:<?= $this->params->get('preloader_bg_vr_position')?> ;
	background-position-y:<?= $this->params->get('preloader_bg_vr_position')?> ;

	<?php endif; ?>
}


.os-spinner {
  position: relative;
  margin: 40vh auto;
}

<?php 



switch ($this->params->get('preloader')) {
case "rotating-plane":
    echo "
		.os-spinner {
		width: 40px;
		height: 40px;
		background-color:$preloader_color;
		-webkit-animation: sk-rotateplane 1.2s infinite ease-in-out;
		animation: sk-rotateplane 1.2s infinite ease-in-out;
		}

		@-webkit-keyframes sk-rotateplane {
			0% { -webkit-transform: perspective(120px) }
			50% { -webkit-transform: perspective(120px) rotateY(180deg) }
			100% { -webkit-transform: perspective(120px) rotateY(180deg)  rotateX(180deg) }
		}

		@keyframes sk-rotateplane {
			0% { 
			transform: perspective(120px) rotateX(0deg) rotateY(0deg);
			-webkit-transform: perspective(120px) rotateX(0deg) rotateY(0deg) 
			} 50% { 
			transform: perspective(120px) rotateX(-180.1deg) rotateY(0deg);
			-webkit-transform: perspective(120px) rotateX(-180.1deg) rotateY(0deg) 
			} 100% { 
			transform: perspective(120px) rotateX(-180deg) rotateY(-179.9deg);
			-webkit-transform: perspective(120px) rotateX(-180deg) rotateY(-179.9deg);
			}
		}
	";
    break;
case "double-bounce":
    echo "
		.os-spinner {
		width: 40px;
		height: 40px;
		}

		.double-bounce1, .double-bounce2 {
		width: 100%;
		height: 100%;
		border-radius: 50%;
		background-color: $preloader_color;
		opacity: 0.6;
		position: absolute;
		top: 0;
		left: 0;

		-webkit-animation: sk-bounce 2.0s infinite ease-in-out;
		animation: sk-bounce 2.0s infinite ease-in-out;
		}

		.double-bounce2 {
		-webkit-animation-delay: -1.0s;
		animation-delay: -1.0s;
		}

		@-webkit-keyframes sk-bounce {
			0%, 100% { -webkit-transform: scale(0.0) }
			50% { -webkit-transform: scale(1.0) }
		}

		@keyframes sk-bounce {
			0%, 100% { 
			transform: scale(0.0);
			-webkit-transform: scale(0.0);
			} 50% { 
			transform: scale(1.0);
			-webkit-transform: scale(1.0);
			}
		}
	";
    break;
case "rectangle-bounce":
    echo "
		.os-spinner {
		width: 50px;
		height: 40px;
		text-align: center;
		font-size: 10px;
		}

		.os-spinner > div {
		  background-color: $preloader_color;
		  height: 100%;
		  width: 6px;
		  display: inline-block;
		  
		  -webkit-animation: sk-stretchdelay 1.2s infinite ease-in-out;
		  animation: sk-stretchdelay 1.2s infinite ease-in-out;
		}

		.os-spinner .rect2 {
		  -webkit-animation-delay: -1.1s;
		  animation-delay: -1.1s;
		}

		.os-spinner .rect3 {
		  -webkit-animation-delay: -1.0s;
		  animation-delay: -1.0s;
		}

		.os-spinner .rect4 {
		  -webkit-animation-delay: -0.9s;
		  animation-delay: -0.9s;
		}

		.os-spinner .rect5 {
		  -webkit-animation-delay: -0.8s;
		  animation-delay: -0.8s;
		}

		@-webkit-keyframes sk-stretchdelay {
		  0%, 40%, 100% { -webkit-transform: scaleY(0.4) }  
		  20% { -webkit-transform: scaleY(1.0) }
		}

		@keyframes sk-stretchdelay {
		  0%, 40%, 100% { 
		    transform: scaleY(0.4);
		    -webkit-transform: scaleY(0.4);
		  }  20% { 
		    transform: scaleY(1.0);
		    -webkit-transform: scaleY(1.0);
		  }
		}
		";
    break;
case "wandering-cubes":
    echo "

    .os-spinner {
	  width: 40px;
	  height: 40px;
	}

	.cube1, .cube2 {
	  background-color: $preloader_color;
	  width: 15px;
	  height: 15px;
	  position: absolute;
	  top: 0;
	  left: 0;
	  
	  -webkit-animation: sk-cubemove 1.8s infinite ease-in-out;
	  animation: sk-cubemove 1.8s infinite ease-in-out;
	}

	.cube2 {
	  -webkit-animation-delay: -0.9s;
	  animation-delay: -0.9s;
	}

	@-webkit-keyframes sk-cubemove {
	  25% { -webkit-transform: translateX(42px) rotate(-90deg) scale(0.5) }
	  50% { -webkit-transform: translateX(42px) translateY(42px) rotate(-180deg) }
	  75% { -webkit-transform: translateX(0px) translateY(42px) rotate(-270deg) scale(0.5) }
	  100% { -webkit-transform: rotate(-360deg) }
	}

	@keyframes sk-cubemove {
	  25% { 
	    transform: translateX(42px) rotate(-90deg) scale(0.5);
	    -webkit-transform: translateX(42px) rotate(-90deg) scale(0.5);
	  } 50% { 
	    transform: translateX(42px) translateY(42px) rotate(-179deg);
	    -webkit-transform: translateX(42px) translateY(42px) rotate(-179deg);
	  } 50.1% { 
	    transform: translateX(42px) translateY(42px) rotate(-180deg);
	    -webkit-transform: translateX(42px) translateY(42px) rotate(-180deg);
	  } 75% { 
	    transform: translateX(0px) translateY(42px) rotate(-270deg) scale(0.5);
	    -webkit-transform: translateX(0px) translateY(42px) rotate(-270deg) scale(0.5);
	  } 100% { 
	    transform: rotate(-360deg);
	    -webkit-transform: rotate(-360deg);
	  }
	}";
    break;
case "pulse":
    echo "
		.os-spinner {
		width: 40px;
		height: 40px;
		background-color: $preloader_color;

		border-radius: 100%;  
		-webkit-animation: sk-scaleout 1.0s infinite ease-in-out;
		animation: sk-scaleout 1.0s infinite ease-in-out;
		}

		@-webkit-keyframes sk-scaleout {
			0% { -webkit-transform: scale(0) }
			100% {
			-webkit-transform: scale(1.0);
			opacity: 0;
			}
		}

		@keyframes sk-scaleout {
			0% { 
			-webkit-transform: scale(0);
			transform: scale(0);
			} 100% {
			-webkit-transform: scale(1.0);
			transform: scale(1.0);
			opacity: 0;
			}
		}
		";
    break;
case "chasing-dots":
    echo "
	.os-spinner {
	width: 40px;
	height: 40px;
	text-align: center;

	-webkit-animation: sk-rotate 2.0s infinite linear;
	animation: sk-rotate 2.0s infinite linear;
	}

	.dot1, .dot2 {
	width: 60%;
	height: 60%;
	display: inline-block;
	position: absolute;
	top: 0;
	background-color: $preloader_color;
	border-radius: 100%;

	-webkit-animation: sk-bounce 2.0s infinite ease-in-out;
	animation: sk-bounce 2.0s infinite ease-in-out;
	}

	.dot2 {
	top: auto;
	bottom: 0;
	-webkit-animation-delay: -1.0s;
	animation-delay: -1.0s;
	}

	@-webkit-keyframes sk-rotate { 100% { -webkit-transform: rotate(360deg) }}

	@keyframes sk-rotate { 100% { transform: rotate(360deg); -webkit-transform: rotate(360deg) }}

	@-webkit-keyframes sk-bounce {
		0%, 100% { -webkit-transform: scale(0.0) }
		50% { -webkit-transform: scale(1.0) }
	}

	@keyframes sk-bounce {
		0%, 100% { 
		transform: scale(0.0);
		-webkit-transform: scale(0.0);
		} 50% { 
		transform: scale(1.0);
		-webkit-transform: scale(1.0);
		}
	}
	";
    break;
case "three-bounce":
    echo "
		.os-spinner {
		width: 70px;
		text-align: center;
		}

		.os-spinner > div {
		width: 18px;
		height: 18px;
		background-color: $preloader_color;

		border-radius: 100%;
		display: inline-block;
		-webkit-animation: sk-bouncedelay 1.4s infinite ease-in-out both;
		animation: sk-bouncedelay 1.4s infinite ease-in-out both;
		}

		.os-spinner .bounce1 {
		-webkit-animation-delay: -0.32s;
		animation-delay: -0.32s;
		}

		.os-spinner .bounce2 {
		-webkit-animation-delay: -0.16s;
		animation-delay: -0.16s;
		}

		@-webkit-keyframes sk-bouncedelay {
			0%, 80%, 100% { -webkit-transform: scale(0) }
			40% { -webkit-transform: scale(1.0) }
		}

		@keyframes sk-bouncedelay {
			0%, 80%, 100% { 
			-webkit-transform: scale(0);
			transform: scale(0);
			} 40% { 
			-webkit-transform: scale(1.0);
			transform: scale(1.0);
			}
		}
	";
    break;
case "sk-circle":
    echo "
		.sk-circle {
		width: 40px;
		height: 40px;
		}
		.sk-circle .sk-child {
		width: 100%;
		height: 100%;
		position: absolute;
		left: 0;
		top: 0;
		}
		.sk-circle .sk-child:before {
		  content: '';
		  display: block;
		  margin: 0 auto;
		  width: 15%;
		  height: 15%;
		  background-color: $preloader_color;
		  border-radius: 100%;
		  -webkit-animation: sk-circleBounceDelay 1.2s infinite ease-in-out both;
		          animation: sk-circleBounceDelay 1.2s infinite ease-in-out both;
		}
		.sk-circle .sk-circle2 {
		  -webkit-transform: rotate(30deg);
		      -ms-transform: rotate(30deg);
		          transform: rotate(30deg); }
		.sk-circle .sk-circle3 {
		  -webkit-transform: rotate(60deg);
		      -ms-transform: rotate(60deg);
		          transform: rotate(60deg); }
		.sk-circle .sk-circle4 {
		  -webkit-transform: rotate(90deg);
		      -ms-transform: rotate(90deg);
		          transform: rotate(90deg); }
		.sk-circle .sk-circle5 {
		  -webkit-transform: rotate(120deg);
		      -ms-transform: rotate(120deg);
		          transform: rotate(120deg); }
		.sk-circle .sk-circle6 {
		  -webkit-transform: rotate(150deg);
		      -ms-transform: rotate(150deg);
		          transform: rotate(150deg); }
		.sk-circle .sk-circle7 {
		  -webkit-transform: rotate(180deg);
		      -ms-transform: rotate(180deg);
		          transform: rotate(180deg); }
		.sk-circle .sk-circle8 {
		  -webkit-transform: rotate(210deg);
		      -ms-transform: rotate(210deg);
		          transform: rotate(210deg); }
		.sk-circle .sk-circle9 {
		  -webkit-transform: rotate(240deg);
		      -ms-transform: rotate(240deg);
		          transform: rotate(240deg); }
		.sk-circle .sk-circle10 {
		  -webkit-transform: rotate(270deg);
		      -ms-transform: rotate(270deg);
		          transform: rotate(270deg); }
		.sk-circle .sk-circle11 {
		  -webkit-transform: rotate(300deg);
		      -ms-transform: rotate(300deg);
		          transform: rotate(300deg); }
		.sk-circle .sk-circle12 {
		  -webkit-transform: rotate(330deg);
		      -ms-transform: rotate(330deg);
		          transform: rotate(330deg); }
		.sk-circle .sk-circle2:before {
		  -webkit-animation-delay: -1.1s;
		          animation-delay: -1.1s; }
		.sk-circle .sk-circle3:before {
		  -webkit-animation-delay: -1s;
		          animation-delay: -1s; }
		.sk-circle .sk-circle4:before {
		  -webkit-animation-delay: -0.9s;
		          animation-delay: -0.9s; }
		.sk-circle .sk-circle5:before {
		  -webkit-animation-delay: -0.8s;
		          animation-delay: -0.8s; }
		.sk-circle .sk-circle6:before {
		  -webkit-animation-delay: -0.7s;
		          animation-delay: -0.7s; }
		.sk-circle .sk-circle7:before {
		  -webkit-animation-delay: -0.6s;
		          animation-delay: -0.6s; }
		.sk-circle .sk-circle8:before {
		  -webkit-animation-delay: -0.5s;
		          animation-delay: -0.5s; }
		.sk-circle .sk-circle9:before {
		  -webkit-animation-delay: -0.4s;
		          animation-delay: -0.4s; }
		.sk-circle .sk-circle10:before {
		  -webkit-animation-delay: -0.3s;
		          animation-delay: -0.3s; }
		.sk-circle .sk-circle11:before {
		  -webkit-animation-delay: -0.2s;
		          animation-delay: -0.2s; }
		.sk-circle .sk-circle12:before {
		  -webkit-animation-delay: -0.1s;
		          animation-delay: -0.1s; }

		@-webkit-keyframes sk-circleBounceDelay {
		  0%, 80%, 100% {
		    -webkit-transform: scale(0);
		            transform: scale(0);
		  } 40% {
		    -webkit-transform: scale(1);
		            transform: scale(1);
		  }
		}

		@keyframes sk-circleBounceDelay {
		  0%, 80%, 100% {
		    -webkit-transform: scale(0);
		            transform: scale(0);
		  } 40% {
		    -webkit-transform: scale(1);
		            transform: scale(1);
		  }
		}
	";
    break;
case "sk-cube-grid":
    echo "
		.sk-cube-grid {
		width: 40px;
		height: 40px;
		}

		.sk-cube-grid .sk-cube {
		  width: 33%;
		  height: 33%;
		  background-color: $preloader_color;
		  float: left;
		  -webkit-animation: sk-cubeGridScaleDelay 1.3s infinite ease-in-out;
		          animation: sk-cubeGridScaleDelay 1.3s infinite ease-in-out; 
		}
		.sk-cube-grid .sk-cube1 {
		  -webkit-animation-delay: 0.2s;
		          animation-delay: 0.2s; }
		.sk-cube-grid .sk-cube2 {
		  -webkit-animation-delay: 0.3s;
		          animation-delay: 0.3s; }
		.sk-cube-grid .sk-cube3 {
		  -webkit-animation-delay: 0.4s;
		          animation-delay: 0.4s; }
		.sk-cube-grid .sk-cube4 {
		  -webkit-animation-delay: 0.1s;
		          animation-delay: 0.1s; }
		.sk-cube-grid .sk-cube5 {
		  -webkit-animation-delay: 0.2s;
		          animation-delay: 0.2s; }
		.sk-cube-grid .sk-cube6 {
		  -webkit-animation-delay: 0.3s;
		          animation-delay: 0.3s; }
		.sk-cube-grid .sk-cube7 {
		  -webkit-animation-delay: 0s;
		          animation-delay: 0s; }
		.sk-cube-grid .sk-cube8 {
		  -webkit-animation-delay: 0.1s;
		          animation-delay: 0.1s; }
		.sk-cube-grid .sk-cube9 {
		  -webkit-animation-delay: 0.2s;
		          animation-delay: 0.2s; }

		@-webkit-keyframes sk-cubeGridScaleDelay {
		  0%, 70%, 100% {
		    -webkit-transform: scale3D(1, 1, 1);
		            transform: scale3D(1, 1, 1);
		  } 35% {
		    -webkit-transform: scale3D(0, 0, 1);
		            transform: scale3D(0, 0, 1); 
		  }
		}

		@keyframes sk-cubeGridScaleDelay {
		  0%, 70%, 100% {
		    -webkit-transform: scale3D(1, 1, 1);
		            transform: scale3D(1, 1, 1);
		  } 35% {
		    -webkit-transform: scale3D(0, 0, 1);
		            transform: scale3D(0, 0, 1);
		  } 
		}
	";
    break;
case "sk-fading-circle":
    echo "
		.sk-fading-circle {
		width: 40px;
		height: 40px;
		}

		.sk-fading-circle .sk-circle {
		width: 100%;
		height: 100%;
		position: absolute;
		left: 0;
		top: 0;
		}

		.sk-fading-circle .sk-circle:before {
		content: '';
		display: block;
		margin: 0 auto;
		width: 15%;
		height: 15%;
		background-color: $preloader_color;
		border-radius: 100%;
		-webkit-animation: sk-circleFadeDelay 1.2s infinite ease-in-out both;
		  animation: sk-circleFadeDelay 1.2s infinite ease-in-out both;
		}
		.sk-fading-circle .sk-circle2 {
		-webkit-transform: rotate(30deg);
		-ms-transform: rotate(30deg);
		  transform: rotate(30deg);
		}
		.sk-fading-circle .sk-circle3 {
		-webkit-transform: rotate(60deg);
		-ms-transform: rotate(60deg);
		  transform: rotate(60deg);
		}
		.sk-fading-circle .sk-circle4 {
		-webkit-transform: rotate(90deg);
		-ms-transform: rotate(90deg);
		  transform: rotate(90deg);
		}
		.sk-fading-circle .sk-circle5 {
		-webkit-transform: rotate(120deg);
		-ms-transform: rotate(120deg);
		  transform: rotate(120deg);
		}
		.sk-fading-circle .sk-circle6 {
		-webkit-transform: rotate(150deg);
		-ms-transform: rotate(150deg);
		  transform: rotate(150deg);
		}
		.sk-fading-circle .sk-circle7 {
		-webkit-transform: rotate(180deg);
		-ms-transform: rotate(180deg);
		  transform: rotate(180deg);
		}
		.sk-fading-circle .sk-circle8 {
		-webkit-transform: rotate(210deg);
		-ms-transform: rotate(210deg);
		  transform: rotate(210deg);
		}
		.sk-fading-circle .sk-circle9 {
		-webkit-transform: rotate(240deg);
		-ms-transform: rotate(240deg);
		  transform: rotate(240deg);
		}
		.sk-fading-circle .sk-circle10 {
		-webkit-transform: rotate(270deg);
		-ms-transform: rotate(270deg);
		  transform: rotate(270deg);
		}
		.sk-fading-circle .sk-circle11 {
		-webkit-transform: rotate(300deg);
		-ms-transform: rotate(300deg);
		  transform: rotate(300deg); 
		}
		.sk-fading-circle .sk-circle12 {
		-webkit-transform: rotate(330deg);
		-ms-transform: rotate(330deg);
		  transform: rotate(330deg); 
		}
		.sk-fading-circle .sk-circle2:before {
		-webkit-animation-delay: -1.1s;
		  animation-delay: -1.1s; 
		}
		.sk-fading-circle .sk-circle3:before {
		-webkit-animation-delay: -1s;
		  animation-delay: -1s; 
		}
		.sk-fading-circle .sk-circle4:before {
		-webkit-animation-delay: -0.9s;
		  animation-delay: -0.9s; 
		}
		.sk-fading-circle .sk-circle5:before {
		-webkit-animation-delay: -0.8s;
		  animation-delay: -0.8s; 
		}
		.sk-fading-circle .sk-circle6:before {
		-webkit-animation-delay: -0.7s;
		  animation-delay: -0.7s; 
		}
		.sk-fading-circle .sk-circle7:before {
		-webkit-animation-delay: -0.6s;
		  animation-delay: -0.6s; 
		}
		.sk-fading-circle .sk-circle8:before {
		-webkit-animation-delay: -0.5s;
		  animation-delay: -0.5s; 
		}
		.sk-fading-circle .sk-circle9:before {
		-webkit-animation-delay: -0.4s;
		  animation-delay: -0.4s;
		}
		.sk-fading-circle .sk-circle10:before {
		-webkit-animation-delay: -0.3s;
		  animation-delay: -0.3s;
		}
		.sk-fading-circle .sk-circle11:before {
		-webkit-animation-delay: -0.2s;
		  animation-delay: -0.2s;
		}
		.sk-fading-circle .sk-circle12:before {
		-webkit-animation-delay: -0.1s;
		  animation-delay: -0.1s;
		}

		@-webkit-keyframes sk-circleFadeDelay {
		0%, 39%, 100% { opacity: 0; }
		40% { opacity: 1; }
		}

		@keyframes sk-circleFadeDelay {
		0%, 39%, 100% { opacity: 0; }
		40% { opacity: 1; } 
		}
	";
    break;
}

echo "</style>";

?>
