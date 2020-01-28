<template>
    <div class="popup">
        <TableColumnCreatePopup/>
        <UserEditPopup/>
    </div>
</template>

<script>
    import TableColumnCreatePopup from '@/popups/table.column.create';
    import UserEditPopup from '@/popups/user.edit';
    export default {
        name: "Popup",
        components: {TableColumnCreatePopup, UserEditPopup},
        props: {
            popup: Object
        }
    }
</script>

<style lang="scss">
    .popup {
        position: absolute;
        top: 0;
        left: 0;
        background-color: #000;
        z-index: 1000;
    }
</style>
