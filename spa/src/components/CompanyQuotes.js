import React, { useMemo } from 'react';
import { Chart } from 'react-charts'

export default function CompanyQuotes({companyQuotes}) {
    if (!companyQuotes || companyQuotes.length === 0) {
        return (<></>);
    }

    const formatDate = (timestamp) => {
        const date = new Date(timestamp * 1000);

        return date.toISOString();
    };

    const chartData = useMemo(() => [
            {
                label: 'Max',
                data: companyQuotes.prices.reduce((memo, info, index) => {
                    memo.push([new Date(info.date * 1000), info.high])

                    return memo;
                }, [])
            },
            {
                label: 'Min',
                data: companyQuotes.prices.reduce((memo, info, index) => {
                    memo.push([new Date(info.date * 1000), info.low])

                    return memo;
                }, [])
            }
        ],
        []
    );

    const axes = useMemo(
        () => [
            { primary: true, type: 'time', position: 'bottom' },
            { type: 'linear', position: 'left' }
        ],
        []
    )

    return (
        <div>
            <div
                style={{
                    width: '100%',
                    height: '300px'
                }}
            >
                <Chart data={chartData} axes={axes}  />
            </div>


            <table className="table">
                <thead>
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Open</th>
                    <th scope="col">High</th>
                    <th scope="col">Low</th>
                    <th scope="col">Close</th>
                    <th scope="col">Volume</th>
                </tr>
                </thead>
                <tbody>

                {companyQuotes.prices.map(info => (
                    <tr key={info.date}>
                        <td>{formatDate(info.date)}</td>
                        <td>{info.open}</td>
                        <td>{info.high}</td>
                        <td>{info.low}</td>
                        <td>{info.close}</td>
                        <td>{info.volume}</td>
                    </tr>
                ))}
                </tbody>
            </table>
        </div>
    );
}
