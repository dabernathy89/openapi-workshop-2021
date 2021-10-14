<!DOCTYPE html>
<html lang="<?php echo str_replace('_', '-', app()->getLocale()) ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>TodoMVC</title>

    <style>
        [v-cloak] {
            display: none;
        }
    </style>
    <link href="/css/todomvc.css" rel="stylesheet">
</head>

<body>
    <section class="todoapp">
        <header class="header">
            <h1>todos</h1>
            <input class="new-todo" autofocus autocomplete="off" placeholder="What needs to be done?"
                v-model="newTodo" @keyup.enter="addTodo" />
        </header>
        <section class="main" v-show="todos.length" v-cloak>
            <input id="toggle-all" class="toggle-all" type="checkbox" v-model="allDone" />
            <label for="toggle-all"></label>
            <ul class="todo-list">
                <li v-for="todo in filteredTodos" class="todo" :key="todo.id"
                    :class="{ completed: todo.completed, editing: todo == editedTodo }">
                    <div class="view">
                        <input class="toggle" type="checkbox" v-model="todo.completed" />
                        <label @dblclick="editTodo(todo)">{{ todo . title }}</label>
                        <button class="destroy" @click="removeTodo(todo)"></button>
                    </div>
                    <input class="edit" type="text" v-model="todo.title" v-todo-focus="todo == editedTodo"
                        @blur="doneEdit(todo)" @keyup.enter="doneEdit(todo)" @keyup.esc="cancelEdit(todo)" />
                </li>
            </ul>
        </section>
        <footer class="footer" v-show="todos.length" v-cloak>
            <span class="todo-count">
                <strong>{{ remaining }}</strong> {{ remaining | pluralize }} left
            </span>
            <ul class="filters">
                <li>
                    <a href="#/all" :class="{ selected: visibility == 'all' }">All</a>
                </li>
                <li>
                    <a href="#/active" :class="{ selected: visibility == 'active' }">Active</a>
                </li>
                <li>
                    <a href="#/completed" :class="{ selected: visibility == 'completed' }">Completed</a>
                </li>
            </ul>
            <button class="clear-completed" @click="removeCompleted" v-show="todos.length > remaining">
                Clear completed
            </button>
        </footer>
    </section>
    <footer class="info">
        <p>Double-click to edit a todo</p>
        <p>Written by <a href="http://evanyou.me">Evan You</a></p>
        <p>Part of <a href="http://todomvc.com">TodoMVC</a></p>
    </footer>

    <script src="/js/vue.global.js"></script>
    <script src="/js/todomvc.js"></script>
</body>

</html>
