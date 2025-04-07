<template>
    <div id="persons-table">
        <table>
            <tbody>
                <tr v-for="person in personsSource" :key="person.id">
                    <td>{{ person.name }}</td>
                    <td>{{ person.email }}</td>
                    <td>{{ person.phone }}</td>
                </tr>
            </tbody>
        </table>
    </div>

</template>

<script>
    export default {
        name: 'persons-table',
        props: {
            personsSource: Array
        },
    }
    
</script>

<style scoped></style>