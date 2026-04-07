import { McpServer } from '@modelcontextprotocol/sdk/server/mcp.js';
import { StdioServerTransport } from '@modelcontextprotocol/sdk/server/stdio.js';
import * as z from 'zod';

/** Sail expõe a app em APP_URL (típico http://localhost, porta 80). */
const DEFAULT_BASE = 'http://localhost/api';

function apiBase() {
    return (process.env.BYTE_LOGISTICS_API_BASE_URL || DEFAULT_BASE).replace(/\/$/, '');
}

function requireToken() {
    const token = process.env.BYTE_LOGISTICS_API_TOKEN;
    if (!token || !String(token).trim()) {
        throw new Error(
            'Defina BYTE_LOGISTICS_API_TOKEN com um token Sanctum (POST /api/sanctum/token).'
        );
    }
    return String(token).trim();
}

/**
 * @param {string} pathWithQuery e.g. /pedidos?page=1&per_page=20
 */
async function apiFetch(pathWithQuery) {
    const token = requireToken();
    const url = `${apiBase()}${pathWithQuery.startsWith('/') ? pathWithQuery : `/${pathWithQuery}`}`;
    const res = await fetch(url, {
        headers: {
            Authorization: `Bearer ${token}`,
            Accept: 'application/json',
        },
    });
    const text = await res.text();
    let data;
    try {
        data = text ? JSON.parse(text) : null;
    } catch {
        data = text;
    }
    if (!res.ok) {
        const msg =
            typeof data === 'object' && data !== null && 'message' in data
                ? String(data.message)
                : `HTTP ${res.status}`;
        throw new Error(`${msg} — ${text.slice(0, 300)}`);
    }
    return data;
}

const mcpServer = new McpServer({
    name: 'byte-logistics',
    version: '1.0.0',
});

mcpServer.registerTool(
    'pedido_obter',
    {
        description:
            'Obtém um pedido da Byte Logistics pelo ID (API autenticada com Sanctum).',
        inputSchema: {
            id: z.number().int().positive().describe('ID numérico do pedido'),
        },
    },
    async ({ id }) => {
        const data = await apiFetch(`/pedidos/${id}`);
        return {
            content: [{ type: 'text', text: JSON.stringify(data, null, 2) }],
        };
    }
);

mcpServer.registerTool(
    'pedido_listar',
    {
        description:
            'Lista pedidos paginados (API GET /api/pedidos). Útil para amostras ou navegação por páginas.',
        inputSchema: {
            page: z.number().int().positive().optional().describe('Número da página (default 1)'),
            per_page: z
                .number()
                .int()
                .min(1)
                .max(100)
                .optional()
                .describe('Itens por página, 1–100 (default 20)'),
        },
    },
    async ({ page, per_page: perPage }) => {
        const p = page ?? 1;
        const pp = perPage ?? 20;
        const q = new URLSearchParams({ page: String(p), per_page: String(pp) });
        const data = await apiFetch(`/pedidos?${q.toString()}`);
        return {
            content: [{ type: 'text', text: JSON.stringify(data, null, 2) }],
        };
    }
);

async function main() {
    const transport = new StdioServerTransport();
    await mcpServer.connect(transport);
}

main().catch((error) => {
    console.error(error);
    process.exit(1);
});
