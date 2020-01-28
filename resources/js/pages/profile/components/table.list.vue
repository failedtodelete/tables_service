<template>
    <div class="tables">
        <div class="tables-empty" v-if="!tables.length">
            <div class="icon-empty"><i class="ft-wifi-off"></i></div>
            <div class="text-empty">Доступные таблицы не найдены</div>
        </div>
        <div class="table-wrap" v-else>
            <div class="tables-list row">
                <div class="tables-list-item col-12 col-sm-6 col-md-4 col-lg-3" v-for="table in tables">
                    <div class="card card-table1" @click="goToTable(table)">
                        <div class="card-body p-1">
                            <fieldset class="form-group m-0">
                                <small class="text-uppercase">{{ moment(table.updated_at).format('YYYY-MM-DD HH:mm') }}</small>
                                <div class="table-name font-medium-1" style="color: #2d2d2d;">{{ table.name }}</div>
                                <div class="table-description">
                                    <small>{{ table.description }}</small>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name: "table.list",
        props: {
            tables: {
                type: Array,
                required: true
            }
        },
        methods: {
            goToTable(table) {
                this.$router.push({ name: 'tables-show', params: { id: table.id } })
            }
        }
    }
</script>

<style lang="scss">
    .tables-empty {
        margin-top: 10rem;
        text-align: center;

        .icon-empty {
            font-size: 4rem;
        }
        .text-empty {
            text-transform: uppercase;
            font-size: 1rem;
        }
    }
    .card-table1 {
        cursor: pointer;

        &:hover {
            background-color: #f7f7f7;
        }
    }
</style>
