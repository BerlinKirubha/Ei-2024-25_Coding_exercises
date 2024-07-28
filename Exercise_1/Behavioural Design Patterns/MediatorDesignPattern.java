import java.util.Date;

interface ChatRoomMediator {
    void showMessage(User user, String message);
}

class ChatRoom implements ChatRoomMediator {
    @Override
    public void showMessage(User user, String message) {
        System.out.println(new Date().toString() + " [" + user.getName() + "] : " + message);
    }
}
abstract class User {
    protected ChatRoomMediator mediator;
    protected String name;

    public User(ChatRoomMediator mediator, String name) {
        this.mediator = mediator;
        this.name = name;
    }

    public abstract void send(String message);

    public abstract void receive(String message);

    public String getName() {
        return name;
    }
}
class ChatUser extends User {
    public ChatUser(ChatRoomMediator mediator, String name) {
        super(mediator, name);
    }

    @Override
    public void send(String message) {
        System.out.println(this.name + " is sending: " + message);
        mediator.showMessage(this, message);
    }

    @Override
    public void receive(String message) {
        System.out.println(this.name + " received: " + message);
    }
}
public class MediatorDesignPattern {
    public static void main(String[] args) {
        ChatRoomMediator mediator = new ChatRoom();

        User user1 = new ChatUser(mediator, "Berlin");
        User user2 = new ChatUser(mediator, "Aarik");

        user1.send("Hello, Aarik!");
        user2.send("Hi, Berlin!");
    }
}
