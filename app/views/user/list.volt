<a href="/user/logout">Выйти</a>
<table class="user-list">
    {%for user in list%}
    <tr>
        <td>{{ user.email }}</td>
        <td>{{ user.name }}</td>
    </tr>

    {%endfor%}

</table>