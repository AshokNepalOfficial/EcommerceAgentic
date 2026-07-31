# EskycodeShop — Omnichannel Agentic E‑Commerce Platform

## Architecture Overview

```mermaid
graph TD
    A[Facebook/Instagram/WhatsApp/TikTok] -->|webhook| B[WebhookController]
    B --> C[NormalizerFactory]
    C -->|Parsed & valid| D[IncomingMessage DTO]
    D --> E[UnifiedConversationService]
    E --> F{E‑Commerce Services}
    F --> G[ProductService / CartService / OrderService]
    F --> H[UserBehaviorService]
    H --> I[UserPersonaService]
    E --> J[UnifiedResponse DTO]
    J --> K[ChannelManager]
    K --> L[FacebookDriver]
    K --> M[WhatsAppDriver]
    K --> N[InstagramDriver]
    K --> O[TikTokDriver]
    L/M/N/O -->|Native API Call| A
```

The platform‑specific knowledge lives only in the **Normalizers** (incoming) and **Drivers** (outgoing). Everything in between works identically regardless of the channel.