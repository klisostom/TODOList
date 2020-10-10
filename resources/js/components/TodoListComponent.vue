<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h1>TODO List</h1>

                        <div class="input-group input-group-lg">
                            <input
                                type="text"
                                class="form-control"
                                aria-label="Large"
                                aria-describedby="inputGroup-sizing-sm"
                                autofocus="autofocus"
                                autocomplete="off"
                                placeholder="What needs to be done?"
                                v-model="newTodo"
                                @keyup.enter="addTodo"
                                v-focus
                            />
                        </div>
                    </div>
                    <div class="card-body">
                        <ul
                            v-for="(todo, index) in todos"
                            :key="todo.id"
                            class="list-group list-group-flush todo-list"
                        >
                            <li class="list-group-item">
                                <div>
                                    <input
                                        type="checkbox"
                                        class="rounded-circle"
                                    />
                                    <label
                                        v-if="!todo.edited"
                                        data-placement="top"
                                        title="Clique para editar"
                                        >{{ todo.title }}</label
                                    >
                                    <input
                                        v-else
                                        type="text"
                                        class="todo-list-edit"
                                        v-model="todo.title"
                                        @blur="doneEdit(todo)"
                                        @keyup.enter="doneEdit(todo)"
                                    />

                                    <button
                                        type="button"
                                        class="close"
                                        aria-label="Close"
                                        @click="removeTodo(index)"
                                    >
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div></div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "todo-list",
    data() {
        return {
            newTodo: "",
            idForTodo: 2,
            todos: [
                {
                    id: 1,
                    title: "FInalizando a lista",
                    completed: false,
                    edited: false
                },
                {
                    id: 2,
                    title: "Estudar",
                    completed: false,
                    edited: false
                }
            ]
        };
    },
    directives: {
        focus: {
            inserted: function (el) {
                el.focus()
            }
        }
    },
    methods: {
        addTodo() {
            if (
                this.newTodo.trim() === undefined ||
                this.newTodo.trim() === ""
            ) {
                return;
            }

            this.todos.push({
                id: this.idForTodo,
                title: this.newTodo,
                completed: false
            });

            this.newTodo = "";
            this.idForTodo++;
        },
        editTodo(todo) {
            todo.edited = true;
        },
        doneEdit(todo) {
            todo.edited = false;
        },
        removeTodo(index) {
            this.todos.splice(index, 1);
        }
    }
};
</script>

<style>
* {
    box-sizing: border-box;
}

ul {
    margin: 0;
    padding: 0;
}

/* Style the list items */
ul li {
    cursor: pointer;
    position: relative;
    padding: 12px 8px 12px 40px;
    font-size: 18px;
    transition: 0.2s;

    /* make the list items unselectable */
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

/* When clicked on, add a background color and strike out text */
ul li.checked {
    background: #888;
    color: #fff;
    text-decoration: line-through;
}

/* Add a "checked" mark when clicked on */
ul li.checked::before {
    content: "";
    position: absolute;
    border-color: #fff;
    border-style: solid;
    border-width: 0 2px 2px 0;
    top: 10px;
    left: 16px;
    transform: rotate(45deg);
    height: 15px;
    width: 7px;
}

.todo-list li:last-child {
    border-bottom: none;
}

.todo-list-edit {
    border: #888;
}

.todo-list-edit:focus {
    outline: 3px solid rgb(255, 145, 0);
}

.todo-list-edit:focus-within {
  box-shadow: 0px 0.2em 2.5em #c4c4c4;
  transform: scale(1.025);
}

</style>
