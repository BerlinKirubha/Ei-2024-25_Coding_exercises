interface TextFormatter {
    String format(String text);
}
class UpperCaseFormatter implements TextFormatter {
    @Override
    public String format(String text) {
        return text.toUpperCase();
    }
}

class LowerCaseFormatter implements TextFormatter {
    @Override
    public String format(String text) {
        return text.toLowerCase();
    }
}

class CapitalizedFormatter implements TextFormatter {
    @Override
    public String format(String text) {
        String[] words = text.split(" ");
        StringBuilder capitalizedText = new StringBuilder();

        for (String word : words) {
            if (word.length() > 0) {
                capitalizedText.append(Character.toUpperCase(word.charAt(0)))
                               .append(word.substring(1).toLowerCase())
                               .append(" ");
            }
        }

        return capitalizedText.toString().trim();
    }
}
class TextEditor {
    private TextFormatter formatter;

    public TextEditor(TextFormatter formatter) {
        this.formatter = formatter;
    }

    public void setFormatter(TextFormatter formatter) {
        this.formatter = formatter;
    }

    public void publishText(String text) {
        String formattedText = formatter.format(text);
        System.out.println(formattedText);
    }
}
public class StrategyDesignPattern {
    public static void main(String[] args) {
        TextEditor editor = new TextEditor(new UpperCaseFormatter());
        editor.publishText("Hello World!");

        editor.setFormatter(new LowerCaseFormatter());
        editor.publishText("Hello World!");

        editor.setFormatter(new CapitalizedFormatter());
        editor.publishText("hello world!");
    }
}
