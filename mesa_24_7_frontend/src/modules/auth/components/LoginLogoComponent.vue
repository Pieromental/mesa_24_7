<template>
  <div class="login-logo-container col-12">
    <div class="login-logo-container-circle">
      <img
        class="login-logo-container-img"
        src="~assets/images/svg/mesa24.svg"
      />
    </div>
  </div>
</template>

<style lang="scss" scoped>
$circle-diameter: 200px;
$border-width: 5px;

.login-logo-container {
  display: flex !important;
  justify-content: center !important;
  margin-bottom: 5%;
}
.login-logo-container-circle {
  display: flex !important;
  justify-content: center !important;

  width: $circle-diameter;
  height: $circle-diameter;
  background: #fff;
  border: $border-width solid rgb(248, 236, 197);
  border-radius: 50%;
}
.login-logo-container-img {
  width: 60%;
}
@media (max-height: 500px) {
  .login-logo-container-circle {
    width: 140px;
    height: 140px;
  }
}
</style>
