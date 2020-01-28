<template>
    <modal name="table.column.create" @before-open="beforeOpen" height="auto">
        <div class="popup-content p-1">
            <form v-on:submit.prevent="submit">
                <div class="popup-header mb-1">
                    <h4 class="m-0 text-uppercase">Создание / Редактирование колонки таблицы</h4>
                    <small class="text-uppercase">Описание действующего функционала</small>
                </div>
                <div class="popup-body">
                    <div class="row">
                        <div class="col-8">
                            <fieldset class="form-group">
                                <small class="text-uppercase">Название колонки</small>
                                <input type="text" class="form-control" v-model="column.name" required>
                            </fieldset>
                        </div>
                        <div class="col-4">
                            <fieldset class="form-group">
                                <small class="text-uppercase">Длина</small>
                                <input type="text" class="form-control" v-model.number="column.width" required>
                            </fieldset>
                        </div>
                        <div class="col-12">
                            <fieldset class="form-group">
                                <small class="text-uppercase">Описание</small>
                                <textarea class="form-control" rows="3" style="resize: none;" v-model="column.description" required></textarea>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="popup-footer">
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-info pull-right">Сохранить</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </modal>
</template>

<script>
    export default {
        name: "table.column.create",
        data() {
            return {
                column: {
                    name: '',
                    width: 120,
                    description: ''
                },
                type: 'create'
            }
        },
        methods: {
            beforeOpen(event) {

                try {
                    if (!'type' in event.params)
                        throw new Error('Не передан режим работы всплывающего окна');

                    if (event.params.type === 'update') {
                        if (!'column' in event.params)
                            throw new Error('Не передан объект колонки для редактирования');

                        this.column = event.params.column;
                    }
                } catch (e) {
                    this.$snotify.error()
                }

                // Внедрение режима работы.
                this.type = event.params.type;
            },
            submit() {
                let eventName = `${this.type}-column`;
                this.$bus.emit(eventName, this.column);

                let defaultData = this.$options.data.apply(this);
                Object.assign(this.$data, defaultData);

                this.$modal.hide('table.column.create')
            }
        }
    }
</script>

<style scoped>

</style>