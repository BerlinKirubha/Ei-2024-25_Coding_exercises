class DVDPlayer {
    public void on() {
        System.out.println("DVD Player is ON");
    }
    public void play(String movie) {
        System.out.println("Playing movie: " + movie);
    }
    public void off() {
        System.out.println("DVD Player is OFF");
    }
}

class Projector {
    public void on() {
        System.out.println("Projector is ON");
    }
    public void setInput(String input) {
        System.out.println("Projector input set to: " + input);
    }
    public void off() {
        System.out.println("Projector is OFF");
    }
}

class Lights {
    public void dim(int level) {
        System.out.println("Lights dimmed to " + level + "%");
    }
    public void on() {
        System.out.println("Lights are ON");
    }
}

class SoundSystem {
    public void on() {
        System.out.println("Sound System is ON");
    }
    public void setVolume(int level) {
        System.out.println("Sound System volume set to " + level);
    }
    public void off() {
        System.out.println("Sound System is OFF");
    }
}
class HomeTheaterFacade {
    private DVDPlayer dvdPlayer;
    private Projector projector;
    private Lights lights;
    private SoundSystem soundSystem;

    public HomeTheaterFacade(DVDPlayer dvdPlayer, Projector projector, Lights lights, SoundSystem soundSystem) {
        this.dvdPlayer = dvdPlayer;
        this.projector = projector;
        this.lights = lights;
        this.soundSystem = soundSystem;
    }

    public void watchMovie(String movie) {
        System.out.println("Get ready to watch a movie...");
        lights.dim(10);
        projector.on();
        projector.setInput("DVD");
        soundSystem.on();
        soundSystem.setVolume(5);
        dvdPlayer.on();
        dvdPlayer.play(movie);
    }

    public void endMovie() {
        System.out.println("Shutting movie theater down...");
        lights.on();
        projector.off();
        soundSystem.off();
        dvdPlayer.off();
    }
}
public class FacadeDesignPattern {
    public static void main(String[] args) {
        DVDPlayer dvdPlayer = new DVDPlayer();
        Projector projector = new Projector();
        Lights lights = new Lights();
        SoundSystem soundSystem = new SoundSystem();

        HomeTheaterFacade homeTheater = new HomeTheaterFacade(dvdPlayer, projector, lights, soundSystem);

        homeTheater.watchMovie("Inception");
        homeTheater.endMovie();
    }
}
