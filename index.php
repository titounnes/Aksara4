<!DOCTYPE html>
<html>
	<head>
		<title>
			Owned!
		</title>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0" />
		<meta name="theme-color" content="#34495e" />
		<meta name="msapplication-navbutton-color" content="#34495e" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="#34495e" />
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<style type="text/css">
			html,
			body
			{
				min-height: 100%
			}

			body
			{
				margin: 0;
				background: #000;
				font-family: Open Sans, Segoe UI, sans-serif
			}

			@keyframes noise-anim
			{
				0%
				{
					clip: rect(58px, 9999px, 77px, 0)
				}
				5%
				{
					clip: rect(16px, 9999px, 6px, 0)
				}
				10%
				{
					clip: rect(17px, 9999px, 43px, 0)
				}
				15%
				{
					clip: rect(4px, 9999px, 52px, 0)
				}
				20%
				{
					clip: rect(13px, 9999px, 64px, 0)
				}
				25%
				{
					clip: rect(14px, 9999px, 49px, 0)
				}
				30%
				{
					clip: rect(20px, 9999px, 60px, 0)
				}
				35%
				{
					clip: rect(32px, 9999px, 57px, 0)
				}
				40%
				{
					clip: rect(79px, 9999px, 92px, 0)
				}
				45%
				{
					clip: rect(1px, 9999px, 51px, 0)
				}
				50%
				{
					clip: rect(67px, 9999px, 80px, 0)
				}
				55%
				{
					clip: rect(25px, 9999px, 78px, 0)
				}
				60%
				{
					clip: rect(31px, 9999px, 65px, 0)
				}
				65%
				{
					clip: rect(23px, 9999px, 80px, 0)
				}
				70%
				{
					clip: rect(82px, 9999px, 77px, 0)
				}
				75%
				{
					clip: rect(35px, 9999px, 49px, 0)
				}
				80%
				{
					clip: rect(79px, 9999px, 82px, 0)
				}
				85%
				{
					clip: rect(8px, 9999px, 64px, 0)
				}
				90%
				{
					clip: rect(65px, 9999px, 42px, 0)
				}
				95%
				{
					clip: rect(80px, 9999px, 100px, 0)
				}
				100%
				{
					clip: rect(100px, 9999px, 110px, 0)
				}
			}

			@keyframes noise-anim-2
			{
				0%
				{
					clip: rect(29px, 9999px, 65px, 0)
				}
				5%
				{
					clip: rect(15px, 9999px, 61px, 0)
				}
				10%
				{
					clip: rect(35px, 9999px, 5px, 0)
				}
				15%
				{
					clip: rect(7px, 9999px, 2px, 0)
				}
				20%
				{
					clip: rect(44px, 9999px, 18px, 0)
				}
				25%
				{
					clip: rect(78px, 9999px, 11px, 0)
				}
				30%
				{
					clip: rect(88px, 9999px, 3px, 0)
				}
				35%
				{
					clip: rect(78px, 9999px, 25px, 0)
				}
				40%
				{
					clip: rect(87px, 9999px, 52px, 0)
				}
				45%
				{
					clip: rect(67px, 9999px, 4px, 0)
				}
				50%
				{
					clip: rect(27px, 9999px, 49px, 0)
				}
				55%
				{
					clip: rect(77px, 9999px, 74px, 0)
				}
				60%
				{
					clip: rect(3px, 9999px, 88px, 0)
				}
				65%
				{
					clip: rect(45px, 9999px, 26px, 0)
				}
				70%
				{
					clip: rect(19px, 9999px, 38px, 0)
				}
				75%
				{
					clip: rect(67px, 9999px, 29px, 0)
				}
				80%
				{
					clip: rect(70px, 9999px, 76px, 0)
				}
				85%
				{
					clip: rect(100px, 9999px, 89px, 0)
				}
				90%
				{
					clip: rect(94px, 9999px, 88px, 0)
				}
				95%
				{
					clip: rect(54px, 9999px, 12px, 0)
				}
				100%
				{
					clip: rect(100px, 9999px, 110px, 0)
				}
			}
			
			.centered
			{
				position: fixed;
				top: 50%;
				left: 50%;
				transform: translate(-50%, -50%);
			}
			
			.glitched
			{
				color: #fff;
				font-size: 100px;
				position: relative;
				margin: 0 auto;
				text-align: center;
				width: 200px;
				position:relative
			}

			.glitched:after
			{
				content: attr(data-text);
				position: absolute;
				left: 2px;
				text-shadow: -2px 0 red;
				top: 0;
				color: #fff;
				background: #000;
				overflow: hidden;
				clip: rect(0, 900px, 0, 0);
				animation: noise-anim .5s infinite linear alternate-reverse
			}

			.glitched:before
			{
				content: attr(data-text);
				position: absolute;
				left: -2px;
				text-shadow: 2px 0 lime;
				top: 0;
				color: #fff;
				background: #000;
				overflow: hidden;
				clip: rect(0, 900px, 0, 0);
				animation: noise-anim-2 1s infinite linear alternate-reverse
			}
		</style>
	</head>
	<body>
		<div class="centered">
			<div class="glitched" data-text="Rrrr!">
				Rrrr!
			</div>
			<div style="text-align:center">
				<a href="//fb.me/abyprogrammer" style="color:#fff;text-decoration:none">
					Owned by @abydahana
				</a>
			</div>
		</div>
	</body>
</html>
