<template>
  <Form ref="loginForm" :model="form" :rules="rules" @keydown.enter.native="handleSubmit">
    <FormItem prop="userName">
      <Input v-model="form.email" placeholder="请输入邮箱">
        <span slot="prepend">
          <Icon :size="16" type="ios-person"></Icon>
        </span>
      </Input>
    </FormItem>
    <FormItem prop="password">
      <Input type="password" v-model="form.password" placeholder="请输入密码">
        <span slot="prepend">
          <Icon :size="14" type="md-lock"></Icon>
        </span>
      </Input>
    </FormItem>
    <FormItem>
      <Button @click="handleSubmit" :loading="saveLoading" type="primary" long>
        <span v-if="!saveLoading">登录</span>
        <span v-else>正在登录...</span>
      </Button>
    </FormItem>
  </Form>
</template>
<script>
export default {
  name: 'LoginForm',
  props: {
    emailRules: {
      type: Array,
      default: () => {
        return [
          { required: true, message: '账号不能为空', trigger: 'blur' }
        ]
      }
    },
    passwordRules: {
      type: Array,
      default: () => {
        return [
          { required: true, message: '密码不能为空', trigger: 'blur' },
          { min: 5, max: 32, message: '密码长度在5~32个字符', trigger: 'blur' }
        ]
      }
    }
  },
  data () {
    return {
      form: {
        email: 'hqfdotcom@gmail.com',
        password: ''
      },
      saveLoading: false
    }
  },
  computed: {
    rules () {
      return {
        email: this.emailRules,
        password: this.passwordRules
      }
    }
  },
  methods: {
    handleSubmit () {
      this.$refs.loginForm.validate((valid) => {
        if (valid) {
          this.saveLoading = true
          this.$emit('on-success-valid', {
            email: this.form.email,
            password: this.form.password
          })
        } else {
          this.saveLoading = false
        }
      })
    },
    loading() {
      this.saveLoading = true
    },
    loadFalse() {
      this.saveLoading = false
    }
  }
}
</script>
