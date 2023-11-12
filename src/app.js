import {Component} from "@wordpress/element";
import {Line, LineChart, CartesianGrid, XAxis, YAxis} from 'recharts';
import {fetch} from "../../../../wp-includes/js/dist/vendor/wp-polyfill-fetch";

class App extends Component {

    constructor(props, context) {
        super(props, context);

        this.state = {
            data: [],
            interval: '7-days',
        }
    }

    getData() {
        const {interval} = this.state;

        fetch(`http://localhost/wordpress/wp-json/rankmath/v1/data?interval=${interval}`)
            .then((res) => res.json())
            .then((res) => {
                this.setState({
                    data: res,
                })
            });


    }

    componentDidMount() {
        this.getData();
    }

    render() {
        const {data, interval} = this.state;
        return (
            <div>
                <table className={"form-table"}>
                    <tbody>
                    <tr>
                        <th>Graph Widget</th>
                        <td>
                            <select defaultValue={interval} onChange={(e) => this.setState({
                                interval: e.target.value,
                            }, () => this.getData())}>
                                <option value="7-days">Last 7 days</option>
                                <option value="15-days">15 Days</option>
                                <option value="30-days">30 Days</option>
                            </select>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <LineChart width={400} height={200} data={data}>
                    <Line type="monotone" dataKey="val" stroke="#8884d8"/>
                    <CartesianGrid stroke="#ccc" />
                    <XAxis dataKey="name" />
                    <YAxis />
                </LineChart>
            </div>
        )
    }
}

export default App;
