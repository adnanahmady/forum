const user = window.AwesomeForum.user;

class Authorization {
    owns(data, key = 'user_id') {
        return data[key] === user.id;
    }

    isAdmin() {
        return ['adnan', 'erfan'].includes(user.name);
    }
}

module.exports = new Authorization;
