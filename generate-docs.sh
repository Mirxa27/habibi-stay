#!/bin/bash

# Install OpenAPI Generator if not installed
if ! command -v openapi-generator &> /dev/null; then
    echo "Installing OpenAPI Generator..."
    npm install @openapitools/openapi-generator-cli -g
    npm install @openapitools/openapi-generator-cli@latest -g
fi

# Create necessary directories
mkdir -p public/docs
mkdir -p clients/{php,javascript,python,typescript,swift}

# Generate HTML documentation with ReDoc
openapi-generator generate \
    -i openapi.yaml \
    -g html2 \
    -o public/docs \
    --additional-properties=inlineSchemaOptions=true

# Generate PHP client library
openapi-generator generate \
    -i openapi.yaml \
    -g php \
    -o clients/php \
    --additional-properties=invokerPackage=HabibiStay\\Api,packageName=habibistay-api-client,composerPackageName=habibistay/api-client

# Generate JavaScript client library
openapi-generator generate \
    -i openapi.yaml \
    -g javascript \
    -o clients/javascript \
    --additional-properties=projectName=habibistay-api-client,usePromises=true,useES6=true

# Generate TypeScript client library
openapi-generator generate \
    -i openapi.yaml \
    -g typescript-fetch \
    -o clients/typescript \
    --additional-properties=npmName=@habibistay/api-client,supportsES6=true,nullSafeAdditionalProps=true

# Generate Python client library
openapi-generator generate \
    -i openapi.yaml \
    -g python \
    -o clients/python \
    --additional-properties=packageName=habibistay_api_client,projectName=habibistay-api-client

# Generate Swift client library
openapi-generator generate \
    -i openapi.yaml \
    -g swift5 \
    -o clients/swift \
    --additional-properties=projectName=HabibiStayAPI,podAuthors="Habibi Stay",swiftPackagePath=HabibiStay/API

# Generate Postman collection
openapi-generator generate \
    -i openapi.yaml \
    -g postman-collection \
    -o public/docs/postman \
    --additional-properties=baseUrl=https://api.habibistay.com

echo "Documentation and client libraries generated successfully!"

# Create README files for each client library
cat > clients/php/README.md << EOL
# Habibi Stay PHP API Client

Official PHP client library for the Habibi Stay API.

## Installation

\`\`\`bash
composer require habibistay/api-client
\`\`\`

## Usage

\`\`\`php
use HabibiStay\\Api\\Client;

\$client = new Client('YOUR_API_KEY');
\$properties = \$client->properties->list();
\`\`\`
EOL

cat > clients/javascript/README.md << EOL
# Habibi Stay JavaScript API Client

Official JavaScript client library for the Habibi Stay API.

## Installation

\`\`\`bash
npm install habibistay-api-client
\`\`\`

## Usage

\`\`\`javascript
import { HabibiStayClient } from 'habibistay-api-client';

const client = new HabibiStayClient('YOUR_API_KEY');
const properties = await client.properties.list();
\`\`\`
EOL

cat > clients/typescript/README.md << EOL
# Habibi Stay TypeScript API Client

Official TypeScript client library for the Habibi Stay API.

## Installation

\`\`\`bash
npm install @habibistay/api-client
\`\`\`

## Usage

\`\`\`typescript
import { HabibiStayClient } from '@habibistay/api-client';

const client = new HabibiStayClient('YOUR_API_KEY');
const properties = await client.properties.list();
\`\`\`
EOL

cat > clients/python/README.md << EOL
# Habibi Stay Python API Client

Official Python client library for the Habibi Stay API.

## Installation

\`\`\`bash
pip install habibistay-api-client
\`\`\`

## Usage

\`\`\`python
from habibistay_api_client import Client

client = Client('YOUR_API_KEY')
properties = client.properties.list()
\`\`\`
EOL

cat > clients/swift/README.md << EOL
# Habibi Stay Swift API Client

Official Swift client library for the Habibi Stay API.

## Installation

### Swift Package Manager

Add the following to your Package.swift:

\`\`\`swift
dependencies: [
    .package(url: "https://github.com/habibistay/swift-api-client.git", from: "1.0.0")
]
\`\`\`

## Usage

\`\`\`swift
import HabibiStayAPI

let client = HabibiStayClient(apiKey: "YOUR_API_KEY")
client.properties.list { result in
    switch result {
    case .success(let properties):
        print(properties)
    case .failure(let error):
        print(error)
    }
}
\`\`\`
EOL

echo "README files created for all client libraries!" 