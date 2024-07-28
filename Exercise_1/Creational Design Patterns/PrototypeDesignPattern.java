interface CloneableDocument {
    CloneableDocument clone();
}

class WordDocument implements CloneableDocument {
    private String content;
    
    public WordDocument(String content) {
        this.content = content;
    }
    
    @Override
    public WordDocument clone() {
        return new WordDocument(this.content);
    }
    
    @Override
    public String toString() {
        return "WordDocument with content: " + content;
    }
}

class PdfDocument implements CloneableDocument {
    private String content;
    
    public PdfDocument(String content) {
        this.content = content;
    }
    
    @Override
    public PdfDocument clone() {
        return new PdfDocument(this.content);
    }
    
    @Override
    public String toString() {
        return "PdfDocument with content: " + content;
    }
}

public class PrototypeDesignPattern {
    public static void main(String[] args) {
        
        WordDocument originalWordDoc = new WordDocument("This is a Word document.");
        PdfDocument originalPdfDoc = new PdfDocument("This is a PDF document.");
        
        
        WordDocument clonedWordDoc = originalWordDoc.clone();
        PdfDocument clonedPdfDoc = originalPdfDoc.clone();
        
        
        System.out.println(clonedWordDoc);
        System.out.println(clonedPdfDoc);
    }
}
