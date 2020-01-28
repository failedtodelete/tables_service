<template>
    <modal name="user.edit" @before-open="beforeOpen" height="auto">
        <div class="popup-content p-1">
            <form v-on:submit.prevent="submit">
                <div class="popup-header mb-1">
                    <h4 class="m-0 text-uppercase">Редактирование</h4>
                    <small class="text-uppercase">Обновление доступных аттрибутов пользователя.</small>
                </div>
                <div class="popup-body">

                    <div class="row">
                        <div class="col-6">
                            <fieldset class="form-group">
                                <small class="text-uppercase">Имя пользователя</small>
                                <input type="text" class="form-control" v-model="user.name" required>
                            </fieldset>
                        </div>
                        <div class="col-6">
                            <fieldset class="form-group">
                                <small class="text-uppercase">Фамилия пользователя</small>
                                <input type="text" class="form-control" v-model="user.last_name" required>
                            </fieldset>
                        </div>
                        <div class="col-6">
                            <fieldset class="form-group">
                                <small class="text-uppercase">Должность</small>
                                <input type="text" class="form-control" v-model="user.position" required>
                            </fieldset>
                        </div>
                        <div class="col-6">
                            <fieldset class="form-group">
                                <small class="text-uppercase">Email (для входа)</small>
                                <input type="text" class="form-control" v-model="user.email" required>
                            </fieldset>
                        </div>
                        <div class="col-6">
                            <fieldset class="form-group">
                                <small class="text-uppercase">Новый пароль</small>
                                <small class="text-uppercase d-block mb-1">Если вы не хотите обновлять пароль пользователя, оставьте это поле пустым</small>
                                <input type="text" class="form-control" v-model="password" placeholder="Новый пароль (6 мин.)">
                            </fieldset>
                        </div>
                        <div class="col-6">
                            <fieldset class="form-group fieldset-submit">
                                <button type="submit" class="btn btn-info pull-right">Сохранить</button>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </modal>
</template>

<script>
    import Axios from 'axios';
    export default {
        name: "user.edit",
        data() {
            return {
                user: {
                    id: undefined,
                    name: '',
                    last_name: '',
                    position: '',
                    email: ''
                },
                password: ''
            }
        },
        methods: {
            beforeOpen(event) {
                if (!'user' in event.params) this.$snotify.error('Не передан пользователь');
                this.user = event.params.user;
            },
            submit() {

                let data = {
                    name: this.user.name,
                    last_name: this.user.last_name,
                    position: this.user.position,
                    email: this.user.email
                };

                if (this.password.length) data.password = this.password;

                Axios.put(`users/${this.user.id}`, data).then(user => {
                    this.$snotify.success('Данные пользователя успешно обновлены');
                    setTimeout(() => {
                        let defaultData = this.$options.data.apply(this);
                        Object.assign(this.$data, defaultData);
                        this.$modal.hide('user.edit')
                    }, 1000);
                })
            }
        }
    }
</script>

<style lang="scss">
    .fieldset-submit {
        display: flex;
        flex-direction: column;
        justify-content: space-between;

        button {
            display: flex;
            width: 100%;
            text-align: center !important;
            justify-content: space-around;
        }
    }
</style>
