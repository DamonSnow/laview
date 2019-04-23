<style lang="less">
  @import './login.less';
</style>

<template>
  <div class="login">
    <canvas class="background"></canvas>
    <div class="login-con">
      <Card icon="log-in" title="欢迎登录" :bordered="false">
        <div class="form-con">
          <login-form ref="loginForm" @on-success-valid="handleSubmit"></login-form>
          <p class="login-tip">输入任意用户名和密码即可</p>
        </div>
      </Card>
    </div>
  </div>
</template>

<script>
import LoginForm from '_c/login-form'
import { mapActions } from 'vuex'
import Particles from 'particlesjs'
export default {
  components: {
    LoginForm,
    Particles
  },
  mounted: function () {
    Particles.init({
      selector: '.background',
      color: '#75A5B7',
      maxParticles: 130,
      connectParticles: true,
      responsive: [
        {
          breakpoint: 768,
          options: {
            maxParticles: 80
          }
        },
        {
          breakpoint: 375,
          options: {
            maxParticles: 50
          }
        }
      ]
    });
  },
  methods: {
    ...mapActions([
      'handleLogin',
      'getUserInfo'
    ]),
    handleSubmit ({ email, password }) {
      let _this = this;
      this.handleLogin({ email, password }).then(res => {
        this.getUserInfo().then(res => {
          this.$router.push({
            name: this.$config.homeName
          })
        })
      }).catch(function (error) {
          _this.$Message.error('登录失败');
          _this.$refs.loginForm.loadFalse();
      })
    }
  }
}
</script>

<style>

</style>
