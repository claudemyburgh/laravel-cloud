<x-app-layout>
    <div class="mb-4 text-sm text-gray-600">
        Sign in with your Ethereum wallet
    </div>

    <div class="mt-4">
        <button onclick="loginWithMetaMask()" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
            Connect with MetaMask
        </button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/web3@1.5.2/dist/web3.min.js"></script>
    <script>
        async function loginWithMetaMask() {
            if (typeof window.ethereum === 'undefined') {
                alert('Please install MetaMask first.');
                return;
            }

            const accounts = await ethereum.request({ method: 'eth_requestAccounts' });
            if (accounts.length === 0) {
                alert('No account selected');
                return;
            }

            const address = accounts[0];
            const message = "{{ config('app.name') }} wants you to sign in with your Ethereum account.";

            try {
                const signature = await ethereum.request({
                    method: 'personal_sign',
                    params: [message, address],
                });

                const response = await fetch('/ethereum/authenticate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        address: address,
                        signature: signature
                    })
                });

                const data = await response.json();
                if (data.success) {
                    window.location.href = '/dashboard';
                } else {
                    alert('Authentication failed');
                }
            } catch (error) {
                console.error(error);
                alert('Error during authentication');
            }
        }
    </script>
</x-app-layout>
