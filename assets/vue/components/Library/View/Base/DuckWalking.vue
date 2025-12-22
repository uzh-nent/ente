<template>
  <div class="duck__wrapper">
    <div class="duck">
      <div class="duck__foot duck__foot--1"></div>
      <div class="duck__foot duck__foot--2"></div>
      <div class="duck duck__inner">
        <div class="duck__mouth"></div>
        <div class="duck__head">
          <div class="duck__eye"></div>
          <div class="duck__white"></div>
        </div>
        <div class="duck__body"></div>
        <div class="duck__wing"></div>
      </div>
    </div>
  </div>
</template>

<script>
/**
 * Source: https://github.com/MdUsmanAnsari/walking-duck-animation
 * MIT licensed
 */
export default {}
</script>

<style>
:root {
  --color-yellow: #ffed02;
  --color-light-orange-1: #fece00;
  --color-light-orange-2: #fcaa1d;
  --color-orange: #f57a00;
  --color-blue-1: #48befd;
  --color-blue-2: #3ba5f1;
  --duck-speed: 1s;
  --duck-speed-half: calc(var(--duck-speed) / 2);
}

.duck__wrapper {
  font-size: 0.4em;
}

.duck {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  position: relative;
}

.duck__wrapper {
  display: -ms-grid;
  display: grid;
  place-content: center;
}

.duck__head {
  -ms-flex-item-align: end;
  align-self: flex-end;
  width: 6em;
  height: 4em;
  border-radius: 8em 8em 0 0;
  background-color: var(--color-yellow);
  position: relative;
  -webkit-transform: translateY(1px);
  transform: translateY(1px);
}

.duck__head::after, .duck__head::before {
  content: "";
  position: absolute;
  border-radius: 1em;
  background-color: var(--color-yellow);
  width: 0.4em;
  height: 2em;
  top: 0;
}

.duck__head::after {
  left: 44%;
  -webkit-transform: translate(-50%, -50%) rotate(-30deg);
  transform: translate(-50%, -50%) rotate(-30deg);
}

.duck__head::before {
  left: 45%;
  -webkit-transform: translate(-50%, -50%) rotate(10deg);
  transform: translate(-50%, -50%) rotate(10deg);
}

.duck__white {
  position: absolute;
  top: 0.8em;
  left: 0.8em;
  width: 0.6em;
  height: 1.3em;
  -webkit-transform: rotate(40deg);
  transform: rotate(40deg);
  border-radius: 50%;
  border-left: 0.2em solid #fff;
}

.duck__eye {
  position: absolute;
  bottom: 0.2em;
  right: 1em;
  width: 0.8em;
  height: 0.8em;
  border-radius: 50%;
  background-color: #000;
  -webkit-animation: eye-animation 1s infinite linear;
  animation: eye-animation 1s infinite linear;
}

.duck__mouth {
  position: absolute;
  right: 0;
  top: 40%;
  width: 1em;
  height: 1.2em;
  -webkit-transform: translate(90%, -50%);
  transform: translate(90%, -50%);
  -webkit-clip-path: polygon(0 0, 100% 40%, 100% 60%, 0% 100%);
  clip-path: polygon(0 0, 100% 40%, 100% 60%, 0% 100%);
  border-radius: 0 1em 1em 0;
  background-color: var(--color-orange);
}

.duck__body {
  width: 9.5em;
  height: 5em;
  border-radius: 1em 0 16em 16em;
  background-color: var(--color-yellow);
  position: relative;
  overflow: hidden;
}

.duck__body::after {
  content: "";
  position: absolute;
  width: 105%;
  height: 200%;
  left: 50%;
  top: -95%;
  -webkit-transform: translate(-50%, 0.02em) rotate(-6deg);
  transform: translate(-50%, 0.02em) rotate(-6deg);
  border-radius: 50%;
  border-bottom: 1em solid var(--color-light-orange-2);
}

.duck__wing {
  position: absolute;
  left: 0.6em;
  top: 55%;
  width: 4em;
  height: 2.4em;
  border-radius: 1em 1em 4em 4em;
  background-color: var(--color-light-orange-1);
  -webkit-transform: translate(0, -50%);
  transform: translate(0, -50%);
  -webkit-transform-origin: right;
  transform-origin: right;
  -webkit-animation: wing-animation var(--duck-speed) linear infinite;
  animation: wing-animation var(--duck-speed) linear infinite;
}

.duck__foot {
  position: absolute;
  width: 0.6em;
  height: 2em;
  background-color: var(--color-orange);
}

.duck__foot::after {
  content: "";
  position: absolute;
  width: 2em;
  height: 0.6em;
  bottom: 0em;
  left: -0.5em;
  background-color: var(--color-orange);
  border-radius: 1em;
}

.duck__foot--1, .duck__foot--2 {
  left: 40%;
  bottom: 0;
  -webkit-transform: translate(-50%, 80%);
  transform: translate(-50%, 80%);
}

.duck__foot--1 {
  -webkit-animation: foot-ans var(--duck-speed) linear infinite;
  animation: foot-ans var(--duck-speed) linear infinite;
}

.duck__foot--2 {
  -webkit-animation: foot-ans var(--duck-speed) var(--duck-speed-half) linear infinite;
  animation: foot-ans var(--duck-speed) var(--duck-speed-half) linear infinite;
}

@-webkit-keyframes foot-ans {
  0% {
    -webkit-transform: translate(-50%, 80%) rotate(0deg);
    transform: translate(-50%, 80%) rotate(0deg);
  }
  10% {
    -webkit-transform: translate(-150%, 80%) rotate(10deg);
    transform: translate(-150%, 80%) rotate(10deg);
  }
  20% {
    -webkit-transform: translate(-150%, 10%) rotate(10deg);
    transform: translate(-150%, 10%) rotate(10deg);
  }
  40% {
    -webkit-transform: translate(400%, 10%) rotate(-20deg);
    transform: translate(400%, 10%) rotate(-20deg);
  }
  60% {
    -webkit-transform: translate(600%, 60%) rotate(-20deg);
    transform: translate(600%, 60%) rotate(-20deg);
  }
  70% {
    -webkit-transform: translate(500%, 60%) rotate(0deg);
    transform: translate(500%, 60%) rotate(0deg);
  }
}

@keyframes foot-ans {
  0% {
    -webkit-transform: translate(-50%, 80%) rotate(0deg);
    transform: translate(-50%, 80%) rotate(0deg);
  }
  10% {
    -webkit-transform: translate(-150%, 80%) rotate(10deg);
    transform: translate(-150%, 80%) rotate(10deg);
  }
  20% {
    -webkit-transform: translate(-150%, 10%) rotate(10deg);
    transform: translate(-150%, 10%) rotate(10deg);
  }
  40% {
    -webkit-transform: translate(400%, 10%) rotate(-20deg);
    transform: translate(400%, 10%) rotate(-20deg);
  }
  60% {
    -webkit-transform: translate(600%, 60%) rotate(-20deg);
    transform: translate(600%, 60%) rotate(-20deg);
  }
  70% {
    -webkit-transform: translate(500%, 60%) rotate(0deg);
    transform: translate(500%, 60%) rotate(0deg);
  }
}

.duck__inner {
  -webkit-animation: bird-up-down var(--duck-speed-half) linear infinite;
  animation: bird-up-down var(--duck-speed-half) linear infinite;
}

@-webkit-keyframes bird-up-down {
  0%,
  100% {
    -webkit-transform: translateY(0.4em);
    transform: translateY(0.4em);
  }
  50% {
    -webkit-transform: translateY(0em);
    transform: translateY(0em);
  }
}

@keyframes bird-up-down {
  0%,
  100% {
    -webkit-transform: translateY(0.4em);
    transform: translateY(0.4em);
  }
  50% {
    -webkit-transform: translateY(0em);
    transform: translateY(0em);
  }
}

@-webkit-keyframes wing-animation {
  0%,
  100% {
    -webkit-transform: translate(0, -50%) rotate(16deg);
    transform: translate(0, -50%) rotate(16deg);
  }
  50% {
    -webkit-transform: translate(0, -50%) rotate(-2deg);
    transform: translate(0, -50%) rotate(-2deg);
  }
}

@keyframes wing-animation {
  0%,
  100% {
    -webkit-transform: translate(0, -50%) rotate(16deg);
    transform: translate(0, -50%) rotate(16deg);
  }
  50% {
    -webkit-transform: translate(0, -50%) rotate(-2deg);
    transform: translate(0, -50%) rotate(-2deg);
  }
}

@-webkit-keyframes eye-animation {
  0%,
  20% {
    -webkit-transform: scaleY(1);
    transform: scaleY(1);
  }
  10% {
    -webkit-transform: scaleY(0);
    transform: scaleY(0);
  }
}

@keyframes eye-animation {
  0%,
  20% {
    -webkit-transform: scaleY(1);
    transform: scaleY(1);
  }
  10% {
    -webkit-transform: scaleY(0);
    transform: scaleY(0);
  }
}

</style>
